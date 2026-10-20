<?php

namespace App\Services\Sharing;

use App\Exceptions\SharingException;
use App\Models\LotteryProvince;
use App\Models\LotterySchedule;
use App\Models\SharingBlock;
use App\Models\SharingOutboxEvent;
use App\Models\TicketShare;
use Carbon\CarbonImmutable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class SharingService
{
    public function __construct(private SharingIdentityService $identity) {}

    public function create(array $auth, array $payload, UploadedFile $image): TicketShare
    {
        $uid = $auth['uid'];
        $ticket = $this->canonicalTicket($payload['ticket'] ?? []);
        $this->assertOpen($ticket);
        $recipientToken = $payload['recipient_token'] ?? '';
        $percent = filter_var($payload['share_percent'] ?? null, FILTER_VALIDATE_INT);
        if ($percent === false || $percent < 10 || $percent > 50) throw new SharingException('invalid_ticket', 422);
        $message = trim((string) ($payload['message'] ?? ''));
        if (mb_strlen($message) > 300) throw new SharingException('invalid_ticket', 422);
        if (($payload['terms_version'] ?? null) !== config('sharing.terms_version')) throw new SharingException('terms_changed', 409);
        $clientTicketId = (string) ($payload['client_ticket_id'] ?? '');
        if (!preg_match('/^[A-Za-z0-9_-]{1,128}$/', $clientTicketId)) throw new SharingException('invalid_ticket', 422);
        [$bytes, $hash] = $this->normaliseImage($image);
        $shareId = 'sh_' . Str::lower(Str::random(24));

        try {
            return DB::transaction(function () use ($uid, $ticket, $recipientToken, $percent, $message, $clientTicketId, $bytes, $hash, $shareId) {
                // client_ticket_id is a stable local identity, never a capability. Once
                // snapshotted it cannot be reused to replace the ticket/image later.
                if (TicketShare::where('owner_uid', $uid)->where('client_ticket_id', $clientTicketId)->lockForUpdate()->exists()) {
                    throw new SharingException('ticket_locked', 409);
                }
                $possibleDuplicate = TicketShare::where('owner_uid', $uid)->where('image_sha256', $hash)->exists();
                $recipient = $this->identity->consumeRecipientToken($uid, $recipientToken);
                if ($recipient->recipient_uid === $uid) throw new SharingException('self_share', 422);

                DB::table('sharing_ticket_locks')->insert([
                    'owner_uid' => $uid, 'client_ticket_id' => $clientTicketId, 'share_id' => $shareId,
                    'created_at' => now(), 'updated_at' => now(),
                ]);

                $path = "ticket-shares/{$shareId}.jpg";
                if (!Storage::disk(config('sharing.image_disk'))->put($path, $bytes, ['visibility' => 'private'])) {
                    throw new SharingException('storage_unavailable', 503);
                }
                $share = TicketShare::create([
                    'share_id' => $shareId, 'owner_uid' => $uid,
                    'recipient_uid' => $recipient->recipient_uid,
                    'recipient_email_hash' => $recipient->email_hash,
                    'recipient_email_encrypted' => $recipient->email_encrypted,
                    'recipient_label' => $recipient->display_label,
                    'client_ticket_id' => $clientTicketId,
                    ...$ticket,
                    'image_path' => $path, 'image_sha256' => $hash,
                    'share_percent' => $percent, 'message' => $message ?: null,
                    'terms_version' => config('sharing.terms_version'),
                    'status' => $recipient->kind === 'registered' ? 'pending' : 'awaiting_registration',
                    'result_status' => 'pending', 'expires_at' => $this->expiresAt($ticket),
                ]);
                $this->audit($share, $uid, 'share_created', ['image_sha256' => $hash]);
                if ($possibleDuplicate) $this->audit($share, $uid, 'possible_duplicate_ticket_image', ['image_sha256' => $hash]);
                if ($share->recipient_uid) $this->outbox($share, $share->recipient_uid, 'share_invitation');
                else $this->outbox($share, null, 'share_invitation_email');
                return $share;
            });
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains(strtolower($e->getMessage()), 'sharing_ticket_locks')) throw new SharingException('ticket_already_shared', 409);
            throw $e;
        }
    }

    public function findForViewer(string $shareId, string $uid, bool $lock = false): TicketShare
    {
        $query = TicketShare::where('share_id', $shareId)
            ->where(fn ($q) => $q->where('owner_uid', $uid)->orWhere('recipient_uid', $uid));
        if ($lock) $query->lockForUpdate();
        $share = $query->first();
        if (!$share) throw new SharingException('not_found', 404);
        return $share;
    }

    public function action(string $action, string $shareId, array $auth, int $expectedVersion, ?string $termsVersion = null): TicketShare
    {
        return DB::transaction(function () use ($action, $shareId, $auth, $expectedVersion, $termsVersion) {
            $share = $this->findForViewer($shareId, $auth['uid'], true);
            $this->expireIfNecessary($share);
            if ($share->recipient_uid && $this->identity->isBlocked($share->owner_uid, $share->recipient_uid)) {
                throw new SharingException('blocked', 403);
            }
            if ($share->version !== $expectedVersion) throw new SharingException('stale_version', 409);

            $isOwner = $share->owner_uid === $auth['uid'];
            if ($action === 'accept') {
                if ($isOwner || $share->status !== 'pending') throw new SharingException('invalid_transition', 409);
                if ($termsVersion !== $share->terms_version || $termsVersion !== config('sharing.terms_version')) throw new SharingException('terms_changed', 409);
                $share->fill(['status' => 'accepted', 'accepted_at' => now(), 'version' => $share->version + 1])->save();
            } elseif ($action === 'decline') {
                if ($isOwner || $share->status !== 'pending') throw new SharingException('invalid_transition', 409);
                $share->fill(['status' => 'declined', 'declined_at' => now(), 'version' => $share->version + 1])->save();
                $this->releaseLock($share);
            } elseif ($action === 'cancel') {
                if (!$isOwner || !in_array($share->status, ['awaiting_registration', 'pending'], true)) throw new SharingException('invalid_transition', 409);
                $share->fill(['status' => 'cancelled', 'cancelled_at' => now(), 'version' => $share->version + 1])->save();
                $this->releaseLock($share);
            } else {
                throw new SharingException('invalid_transition', 409);
            }
            $this->audit($share, $auth['uid'], "share_{$action}");
            $otherUid = $isOwner ? $share->recipient_uid : $share->owner_uid;
            if ($otherUid) $this->outbox($share, $otherUid, 'share_updated');
            return $share->fresh();
        });
    }

    public function list(string $uid, string $box, int $limit, ?string $cursor): array
    {
        if (!in_array($box, ['sent', 'received'], true)) throw new SharingException('invalid_box', 422);
        $limit = min(max($limit, 1), 50);
        $query = TicketShare::query()->where($box === 'sent' ? 'owner_uid' : 'recipient_uid', $uid)->orderByDesc('created_at')->orderByDesc('id');
        if ($cursor) {
            $decoded = json_decode(base64_decode(strtr($cursor, '-_', '+/'), true) ?: '', true);
            if (!is_array($decoded) || !isset($decoded['at'], $decoded['id'])) throw new SharingException('invalid_cursor', 422);
            $query->where(fn ($q) => $q->where('created_at', '<', $decoded['at'])->orWhere(fn ($q) => $q->where('created_at', $decoded['at'])->where('id', '<', $decoded['id'])));
        }
        $items = $query->limit($limit + 1)->get();
        $next = null;
        if ($items->count() > $limit) {
            // Remove the look-ahead record, then cursor from the last record that
            // was actually returned. The next query uses "< cursor", so using the
            // removed record here would skip it entirely.
            $items->pop();
            $lastReturned = $items->last();
            $next = rtrim(strtr(base64_encode(json_encode([
                // Cursor is opaque. Keep the database-local precision rather than
                // UTC ISO-8601: MySQL DATETIME has no timezone and comparing a UTC
                // value to an Asia/Ho_Chi_Minh stored value would skip a whole page.
                'at' => $lastReturned->created_at->format('Y-m-d H:i:s.u'),
                'id' => $lastReturned->id,
            ])), '+/', '-_'), '=');
        }
        return [$items, $next];
    }

    public function serialise(TicketShare $share, string $viewerUid): array
    {
        $isOwner = $share->owner_uid === $viewerUid;
        $canSeeImage = in_array($share->status, ['awaiting_registration', 'pending', 'accepted'], true)
            && ($isOwner || $share->recipient_uid === $viewerUid);
        return [
            'share_id' => $share->share_id, 'version' => $share->version,
            'viewer_role' => $isOwner ? 'sender' : 'recipient',
            'sender' => $this->sender($share->owner_uid),
            'recipient_label' => $share->recipient_label,
            'ticket' => [
                'number' => $share->number, 'province' => $share->province, 'province_code' => $share->province_code,
                'region' => $share->region, 'draw_date' => $share->draw_date->toDateString(),
                'image_url' => $canSeeImage && $share->image_path ? $this->imageUrl($share) : null,
            ],
            'share_percent' => $share->share_percent, 'message' => $share->message,
            'status' => $share->status, 'result_status' => $share->result_status,
            'result_final' => $share->result_final, 'matched_prizes' => $share->matched_prizes ?? [],
            'created_at' => $share->created_at->utc()->toISOString(), 'expires_at' => $share->expires_at->utc()->toISOString(),
            'terms_version' => $share->terms_version,
            'sender_blocked' => !$isOwner && SharingBlock::where('blocker_uid', $viewerUid)->where('blocked_uid', $share->owner_uid)->exists(),
        ];
    }

    public function expireIfNecessary(TicketShare $share): void
    {
        if (in_array($share->status, ['awaiting_registration', 'pending'], true) && $share->expires_at->isPast()) {
            $share->fill(['status' => 'expired', 'expired_at' => now(), 'version' => $share->version + 1])->save();
            $this->releaseLock($share);
            $this->audit($share, null, 'share_expired');
        }
    }

    private function canonicalTicket(array $ticket): array
    {
        $number = (string) ($ticket['number'] ?? '');
        $code = strtoupper((string) ($ticket['province_code'] ?? ''));
        $region = (string) ($ticket['region'] ?? '');
        $date = (string) ($ticket['draw_date'] ?? '');
        if (!preg_match('/^\d{5,6}$/', $number) || !in_array($region, config('lottery.regions'), true)
            || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) throw new SharingException('invalid_ticket', 422);
        try {
            $drawDay = CarbonImmutable::createFromFormat('!Y-m-d', $date, 'Asia/Ho_Chi_Minh');
            if ($drawDay->format('Y-m-d') !== $date) throw new \InvalidArgumentException();
        } catch (\Throwable) {
            throw new SharingException('invalid_ticket', 422);
        }
        $province = LotteryProvince::where('code', $code)->where('region', $region)->first();
        if (!$province || !LotterySchedule::where('province_id', $province->id)->where('day_of_week', $drawDay->dayOfWeekIso)->exists()) {
            throw new SharingException('invalid_ticket', 422);
        }
        return ['number' => $number, 'province' => $province->name, 'province_code' => $province->code, 'region' => $region, 'draw_date' => $date];
    }

    private function assertOpen(array $ticket): void
    {
        if ($this->expiresAt($ticket)->isPast()) throw new SharingException('draw_closed', 409);
    }

    private function expiresAt(array $ticket): CarbonImmutable
    {
        $time = config("lottery.draw_times.{$ticket['region']}");
        return CarbonImmutable::createFromFormat('Y-m-d H:i', "{$ticket['draw_date']} {$time}", 'Asia/Ho_Chi_Minh')->utc();
    }

    private function normaliseImage(UploadedFile $image): array
    {
        if (!$image->isValid() || $image->getSize() > config('sharing.image_max_bytes')) throw new SharingException('image_too_large', 422);
        $info = @getimagesize($image->getRealPath());
        if (!$info || $info['mime'] !== 'image/jpeg' || ($info[0] * $info[1]) > config('sharing.image_max_pixels')) throw new SharingException('invalid_ticket', 422);
        if (!function_exists('imagecreatefromstring') || !function_exists('imagejpeg')) throw new SharingException('image_processing_unavailable', 503);
        $source = @imagecreatefromstring((string) file_get_contents($image->getRealPath()));
        if (!$source) throw new SharingException('invalid_ticket', 422);
        ob_start(); imagejpeg($source, null, 88); $bytes = (string) ob_get_clean(); imagedestroy($source);
        return [$bytes, hash('sha256', $bytes)];
    }

    private function imageUrl(TicketShare $share): string
    {
        return URL::temporarySignedRoute('api.sharing.image', now()->addMinutes((int) config('sharing.image_url_minutes')), ['shareId' => $share->share_id]);
    }

    private function sender(string $uid): array
    {
        $profile = \App\Models\SharingProfile::where('firebase_uid', $uid)->first();
        return ['public_id' => $profile?->public_id ?? 'LL_UNKNOWN', 'display_name' => $profile?->display_name ?? 'Lotto Live'];
    }

    private function releaseLock(TicketShare $share): void
    {
        DB::table('sharing_ticket_locks')->where('share_id', $share->share_id)->delete();
    }

    private function outbox(TicketShare $share, ?string $recipientUid, string $type): void
    {
        $version = $share->result_version;
        SharingOutboxEvent::firstOrCreate(['dedup_key' => implode(':', [$share->share_id, $type, $version, $recipientUid ?: 'email'])], [
            'event_id' => 'evt_' . Str::lower(Str::random(24)), 'share_id' => $share->share_id,
            'recipient_uid' => $recipientUid, 'type' => $type, 'result_version' => $version,
            'payload' => ['share_id' => $share->share_id], 'status' => 'pending', 'available_at' => now(),
        ]);
    }

    private function audit(TicketShare $share, ?string $actorUid, string $type, array $metadata = []): void
    {
        DB::table('sharing_audit_events')->insert(['share_id' => $share->share_id, 'actor_uid' => $actorUid, 'type' => $type, 'metadata' => json_encode($metadata), 'request_id' => (string) Str::uuid(), 'created_at' => now(), 'updated_at' => now()]);
    }
}
