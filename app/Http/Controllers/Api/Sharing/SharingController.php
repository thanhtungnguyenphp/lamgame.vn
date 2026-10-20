<?php

namespace App\Http\Controllers\Api\Sharing;

use App\Exceptions\SharingException;
use App\Http\Controllers\Controller;
use App\Models\SharingBlock;
use App\Models\SharingDevice;
use App\Models\SharingProfile;
use App\Models\SharingReport;
use App\Models\TicketShare;
use App\Services\Sharing\IdempotencyService;
use App\Services\Sharing\SharingIdentityService;
use App\Services\Sharing\SharingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SharingController extends Controller
{
    public function me(Request $request, SharingIdentityService $identity): JsonResponse
    {
        $profile = $identity->profile($this->auth($request));
        return $this->ok(['public_id' => $profile->public_id, 'display_name' => $profile->display_name]);
    }

    public function resolve(Request $request, SharingIdentityService $identity, IdempotencyService $idempotency): JsonResponse
    {
        $data = $request->validate(['kind' => 'required|in:email,public_id', 'value' => 'required|string|max:254']);
        return $this->idempotent($request, 'recipients.resolve', $data, $idempotency, fn () => $this->ok($identity->resolve($this->auth($request), $data['kind'], $data['value'])));
    }

    public function store(Request $request, SharingService $sharing, IdempotencyService $idempotency): JsonResponse
    {
        $request->validate(['payload' => 'required|string', 'image' => 'required|file|max:8192']);
        $payload = json_decode($request->input('payload'), true);
        if (!is_array($payload)) throw new SharingException('invalid_ticket', 422);
        $imageHash = hash_file('sha256', $request->file('image')->getRealPath());
        return $this->idempotent($request, 'shares.store', ['payload' => $payload, 'image_sha256' => $imageHash], $idempotency, function () use ($request, $sharing, $payload) {
            $share = $sharing->create($this->auth($request), $payload, $request->file('image'));
            return $this->ok($sharing->serialise($share, $this->auth($request)['uid']), 201);
        });
    }

    public function index(Request $request, SharingService $sharing): JsonResponse
    {
        $data = $request->validate(['box' => 'required|in:received,sent', 'limit' => 'nullable|integer|min:1|max:50', 'cursor' => 'nullable|string|max:400']);
        [$items, $next] = $sharing->list($this->auth($request)['uid'], $data['box'], $data['limit'] ?? 20, $data['cursor'] ?? null);
        return $this->ok(['items' => $items->map(fn ($share) => $sharing->serialise($share, $this->auth($request)['uid']))->values(), 'next_cursor' => $next]);
    }

    public function show(Request $request, string $shareId, SharingService $sharing): JsonResponse
    {
        $share = $sharing->findForViewer($shareId, $this->auth($request)['uid']);
        $sharing->expireIfNecessary($share);
        return $this->ok($sharing->serialise($share->fresh(), $this->auth($request)['uid']));
    }

    public function action(Request $request, string $shareId, string $action, SharingService $sharing, IdempotencyService $idempotency): JsonResponse
    {
        $data = $request->validate(['expected_version' => 'required|integer|min:1', 'terms_version' => $action === 'accept' ? 'required|string|max:40' : 'nullable|string|max:40']);
        return $this->idempotent($request, "shares.{$action}", ['share_id' => $shareId, ...$data], $idempotency, function () use ($request, $shareId, $action, $data, $sharing) {
            $share = $sharing->action($action, $shareId, $this->auth($request), $data['expected_version'], $data['terms_version'] ?? null);
            return $this->ok($sharing->serialise($share, $this->auth($request)['uid']));
        });
    }

    public function putDevice(Request $request, string $installationId): JsonResponse
    {
        if (!preg_match('/^[A-Za-z0-9_-]{1,128}$/', $installationId)) throw new SharingException('invalid_installation_id', 422);
        $data = $request->validate(['fcm_token' => 'required|string|max:500', 'platform' => 'required|in:android,ios']);
        $uid = $this->auth($request)['uid'];
        DB::transaction(function () use ($installationId, $data, $uid) {
            // A token can belong to exactly one account. Rebinding on intentional account
            // switch removes the previous association, so the former account stops receiving pushes.
            SharingDevice::where('fcm_token', $data['fcm_token'])->where('installation_id', '!=', $installationId)->delete();
            $device = SharingDevice::where('installation_id', $installationId)->lockForUpdate()->first();
            if ($device) $device->update(['firebase_uid' => $uid, ...$data]);
            else SharingDevice::create(['installation_id' => $installationId, 'firebase_uid' => $uid, ...$data]);
        });
        return $this->ok([]);
    }

    public function deleteDevice(Request $request, string $installationId): JsonResponse
    {
        SharingDevice::where('installation_id', $installationId)->where('firebase_uid', $this->auth($request)['uid'])->delete();
        return $this->ok([]);
    }

    public function block(Request $request, string $publicId, SharingIdentityService $identity, SharingService $sharing): JsonResponse
    {
        $data = $request->validate(['blocked' => 'required|boolean']);
        $uid = $this->auth($request)['uid'];
        $target = SharingProfile::where('public_id', $publicId)->first();
        if (!$target) throw new SharingException('recipient_unavailable', 404);
        if ($target->firebase_uid === $uid) throw new SharingException('self_share', 422);
        DB::transaction(function () use ($data, $uid, $target, $sharing) {
            if (!$data['blocked']) {
                SharingBlock::where('blocker_uid', $uid)->where('blocked_uid', $target->firebase_uid)->delete();
                return;
            }
            SharingBlock::firstOrCreate(['blocker_uid' => $uid, 'blocked_uid' => $target->firebase_uid]);
            TicketShare::whereIn('status', ['awaiting_registration', 'pending'])
                ->where(fn ($q) => $q->where(['owner_uid' => $uid, 'recipient_uid' => $target->firebase_uid])->orWhere(['owner_uid' => $target->firebase_uid, 'recipient_uid' => $uid]))
                ->lockForUpdate()->get()->each(function ($share) use ($sharing, $uid) {
                    $share->update(['status' => 'cancelled', 'cancelled_at' => now(), 'version' => $share->version + 1]);
                    DB::table('sharing_ticket_locks')->where('share_id', $share->share_id)->delete();
                });
        });
        return $this->ok([]);
    }

    public function report(Request $request, SharingService $sharing, IdempotencyService $idempotency): JsonResponse
    {
        $data = $request->validate(['share_id' => 'required|string|regex:/^[A-Za-z0-9_-]{1,128}$/', 'reason' => 'required|in:spam,fraud,harassment,inappropriate']);
        return $this->idempotent($request, 'reports.store', $data, $idempotency, function () use ($request, $data, $sharing) {
            $uid = $this->auth($request)['uid'];
            $share = $sharing->findForViewer($data['share_id'], $uid);
            \App\Models\SharingReport::firstOrCreate(['reporter_uid' => $uid, 'share_id' => $share->share_id], [
                'reason' => $data['reason'],
                'moderation_snapshot' => ['ticket' => ['province_code' => $share->province_code, 'draw_date' => $share->draw_date->toDateString()], 'message' => $share->message, 'image_sha256' => $share->image_sha256],
            ]);
            return $this->ok([]);
        });
    }

    // Signed image URLs are bearer URLs intentionally; authorization was checked when issued.
    public function image(Request $request, string $shareId)
    {
        if (!$request->hasValidSignature()) abort(403);
        $share = TicketShare::where('share_id', $shareId)->firstOrFail();
        if (!$share->image_path || !Storage::disk(config('sharing.image_disk'))->exists($share->image_path)) abort(404);
        return response()->file(Storage::disk(config('sharing.image_disk'))->path($share->image_path), [
            'Content-Type' => 'image/jpeg', 'Cache-Control' => 'private, no-store',
        ]);
    }

    private function auth(Request $request): array { return $request->attributes->get('sharing.auth'); }

    private function ok(array $data, int $status = 200): JsonResponse { return response()->json(['status' => 'ok', 'data' => $data], $status); }

    private function idempotent(Request $request, string $route, array $payload, IdempotencyService $service, callable $callback): JsonResponse
    {
        $hash = hash('sha256', json_encode($this->sortRecursive($payload), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        if ($replay = $service->responseForReplay($request, $route, $hash)) return $replay;
        $record = $service->reserve($request, $route, $hash);
        try {
            $response = $callback();
            $service->finish($record, $response->getData(true), $response->status());
            return $response;
        } catch (\Throwable $e) {
            $record->delete();
            throw $e;
        }
    }

    private function sortRecursive(mixed $value): mixed
    {
        if (!is_array($value)) return $value;
        foreach ($value as $key => $item) $value[$key] = $this->sortRecursive($item);
        if (!array_is_list($value)) ksort($value);
        return $value;
    }
}
