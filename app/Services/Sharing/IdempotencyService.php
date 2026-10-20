<?php

namespace App\Services\Sharing;

use App\Exceptions\SharingException;
use App\Models\SharingIdempotencyKey;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class IdempotencyService
{
    public function reserve(Request $request, string $route, string $payloadHash): SharingIdempotencyKey
    {
        $key = (string) $request->header('Idempotency-Key');
        if (!preg_match('/^[a-f0-9]{32}$/', $key)) {
            throw new SharingException('invalid_idempotency_key', 422);
        }

        $uid = $request->attributes->get('sharing.auth')['uid'];
        $keyHash = hash('sha256', $key);
        $record = SharingIdempotencyKey::where([
            'firebase_uid' => $uid, 'route' => $route, 'key_hash' => $keyHash,
        ])->first();

        if ($record) {
            if (!hash_equals($record->payload_hash, $payloadHash)) {
                throw new SharingException('idempotency_key_reused', 409);
            }
            if ($record->response_body !== null) {
                throw new SharingException('idempotency_replay', 208);
            }
            throw new SharingException('idempotency_in_progress', 409);
        }

        try {
            return SharingIdempotencyKey::create([
                'firebase_uid' => $uid,
                'route' => $route,
                'key_hash' => $keyHash,
                'payload_hash' => $payloadHash,
                'expires_at' => Carbon::now('UTC')->addDays((int) config('sharing.idempotency_days')),
            ]);
        } catch (\Throwable) {
            // A concurrent request won the unique key. Its next retry will replay.
            throw new SharingException('idempotency_in_progress', 409);
        }
    }

    public function finish(SharingIdempotencyKey $record, array $body, int $status): void
    {
        $record->update(['response_body' => $body, 'status_code' => $status]);
    }

    public function responseForReplay(Request $request, string $route, string $payloadHash): ?\Illuminate\Http\JsonResponse
    {
        $key = (string) $request->header('Idempotency-Key');
        if (!preg_match('/^[a-f0-9]{32}$/', $key)) return null;
        $uid = $request->attributes->get('sharing.auth')['uid'];
        $record = SharingIdempotencyKey::where([
            'firebase_uid' => $uid, 'route' => $route, 'key_hash' => hash('sha256', $key),
        ])->first();
        if (!$record) return null;
        if (!hash_equals($record->payload_hash, $payloadHash)) throw new SharingException('idempotency_key_reused', 409);
        if ($record->response_body === null) throw new SharingException('idempotency_in_progress', 409);
        return response()->json($record->response_body, $record->status_code ?: 200);
    }
}
