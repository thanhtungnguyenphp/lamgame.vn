<?php

namespace App\Services\Sharing;

use App\Exceptions\SharingException;
use App\Models\SharingBlock;
use App\Models\SharingProfile;
use App\Models\SharingRecipientToken;
use App\Models\SharingOutboxEvent;
use App\Models\TicketShare;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class SharingIdentityService
{
    public function profile(array $auth): SharingProfile
    {
        $email = $this->normaliseEmail($auth['email'] ?? null);
        if (!$email) throw new SharingException('email_unverified', 403);

        $profile = SharingProfile::firstOrNew(['firebase_uid' => $auth['uid']]);
        if (!$profile->exists) {
            $profile->public_id = $this->newPublicId();
        }
        $profile->display_name = $this->displayName($auth['name'] ?? null, $profile->display_name ?? null);
        $profile->email_hash = $this->emailHash($email);
        $profile->email_encrypted = Crypt::encryptString($email);
        $profile->email_verified_at = Carbon::now('UTC');
        $profile->save();
        $this->claimInvitations($profile);
        return $profile;
    }

    public function resolve(array $auth, string $kind, string $value): array
    {
        $sender = $this->profile($auth);
        $recipient = null;
        $emailHash = null;
        $email = null;
        $label = null;

        if ($kind === 'public_id') {
            $recipient = SharingProfile::where('public_id', $value)->first();
            if (!$recipient) throw new SharingException('recipient_unavailable', 404);
            $label = $recipient->display_name;
        } elseif ($kind === 'email') {
            $email = $this->normaliseEmail($value);
            if (!$email) throw new SharingException('recipient_unavailable', 422);
            $emailHash = $this->emailHash($email);
            $recipient = SharingProfile::where('email_hash', $emailHash)->where('email_discoverable', true)->first();
            $label = $recipient?->display_name ?? $this->maskEmail($email);
        } else {
            throw new SharingException('recipient_unavailable', 422);
        }

        if ($recipient && $recipient->firebase_uid === $sender->firebase_uid) throw new SharingException('self_share', 422);
        if ($recipient && $this->isBlocked($sender->firebase_uid, $recipient->firebase_uid)) throw new SharingException('blocked', 403);

        $raw = bin2hex(random_bytes(32));
        SharingRecipientToken::create([
            'token_hash' => hash('sha256', $raw),
            'sender_uid' => $sender->firebase_uid,
            'recipient_uid' => $recipient?->firebase_uid,
            'email_hash' => $emailHash,
            'email_encrypted' => isset($email) ? Crypt::encryptString($email) : null,
            'kind' => $recipient ? 'registered' : 'email_invitation',
            'display_label' => $label,
            'expires_at' => Carbon::now('UTC')->addMinutes((int) config('sharing.recipient_token_minutes')),
        ]);

        return [
            'recipient_token' => $raw,
            'kind' => $recipient ? 'registered' : 'email_invitation',
            'display_label' => $label,
            'public_id' => $recipient?->public_id,
        ];
    }

    public function consumeRecipientToken(string $senderUid, string $raw): SharingRecipientToken
    {
        $token = SharingRecipientToken::where('token_hash', hash('sha256', $raw))->lockForUpdate()->first();
        if (!$token || $token->sender_uid !== $senderUid || $token->used_at || $token->expires_at->isPast()) {
            throw new SharingException('recipient_token_expired', 422);
        }
        if ($token->recipient_uid && $this->isBlocked($senderUid, $token->recipient_uid)) throw new SharingException('blocked', 403);
        $token->update(['used_at' => Carbon::now('UTC')]);
        return $token;
    }

    public function isBlocked(string $firstUid, string $secondUid): bool
    {
        return SharingBlock::where(fn ($q) => $q->where('blocker_uid', $firstUid)->where('blocked_uid', $secondUid))
            ->orWhere(fn ($q) => $q->where('blocker_uid', $secondUid)->where('blocked_uid', $firstUid))
            ->exists();
    }

    public function emailHash(string $email): string
    {
        return hash_hmac('sha256', $email, (string) config('app.key'));
    }

    public function normaliseEmail(?string $email): ?string
    {
        if (!$email) return null;
        $email = mb_strtolower(trim($email));
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
    }

    private function newPublicId(): string
    {
        do {
            $id = 'LL_' . strtoupper(Str::random(9));
        } while (SharingProfile::where('public_id', $id)->exists());
        return $id;
    }

    private function displayName(?string $candidate, ?string $existing): string
    {
        $candidate = trim((string) $candidate);
        return mb_substr($candidate !== '' ? $candidate : ($existing ?: 'Lotto Live'), 0, 120);
    }

    private function maskEmail(string $email): string
    {
        [$local, $domain] = explode('@', $email, 2);
        return mb_substr($local, 0, 1) . '***@' . $domain;
    }

    private function claimInvitations(SharingProfile $profile): void
    {
        TicketShare::where('recipient_email_hash', $profile->email_hash)
            ->where('status', 'awaiting_registration')
            ->where('expires_at', '>', now())
            ->lockForUpdate()->get()->each(function (TicketShare $share) use ($profile) {
                if ($this->isBlocked($share->owner_uid, $profile->firebase_uid)) return;
                $share->update(['recipient_uid' => $profile->firebase_uid, 'status' => 'pending', 'version' => $share->version + 1]);
                SharingOutboxEvent::firstOrCreate([
                    'dedup_key' => "{$share->share_id}:share_invitation:{$share->result_version}:{$profile->firebase_uid}",
                ], [
                    'event_id' => 'evt_' . Str::lower(Str::random(24)), 'share_id' => $share->share_id,
                    'recipient_uid' => $profile->firebase_uid, 'type' => 'share_invitation',
                    'result_version' => $share->result_version, 'payload' => ['share_id' => $share->share_id],
                    'status' => 'pending', 'available_at' => now(),
                ]);
            });
    }
}
