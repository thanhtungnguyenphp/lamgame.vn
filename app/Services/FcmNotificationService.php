<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmNotificationService
{
    private string $serverKey;

    public function __construct()
    {
        $this->serverKey = config('firebase.fcm.server_key', '');
    }

    /**
     * Gửi push notification đến FCM topic.
     */
    public function sendToTopic(string $topic, array $notification, array $data = []): bool
    {
        return $this->send([
            'to'           => "/topics/{$topic}",
            'notification' => $notification,
            'data'         => $data,
        ]);
    }

    /**
     * Gửi push notification đến FCM token cụ thể.
     */
    public function sendToToken(string $token, array $notification, array $data = []): bool
    {
        return $this->send([
            'to'           => $token,
            'notification' => $notification,
            'data'         => $data,
        ]);
    }

    private function send(array $payload): bool
    {
        if (empty($this->serverKey)) {
            Log::warning('FCM server key not configured');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => "key={$this->serverKey}",
                'Content-Type'  => 'application/json',
            ])->post('https://fcm.googleapis.com/fcm/send', $payload);

            if ($response->successful()) {
                return true;
            }

            Log::error('FCM send failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('FCM send exception', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
