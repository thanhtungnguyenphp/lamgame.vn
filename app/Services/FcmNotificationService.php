<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Firebase\JWT\JWT;

class FcmNotificationService
{
    private string $projectId;
    private string $credentialsPath;

    public function __construct()
    {
        $this->projectId = config('firebase.project_id', '');
        $this->credentialsPath = config('firebase.credentials', '');
    }

    public function sendToTopic(string $topic, array $notification, array $data = []): bool
    {
        return $this->send([
            'message' => [
                'topic'        => $topic,
                'notification' => $notification,
                'data'         => $this->stringifyData($data),
            ],
        ]);
    }

    public function sendToToken(string $token, array $notification, array $data = []): bool
    {
        return $this->send([
            'message' => [
                'token'        => $token,
                'notification' => $notification,
                'data'         => $this->stringifyData($data),
            ],
        ]);
    }

    private function send(array $payload): bool
    {
        $token = $this->getAccessToken();
        if (!$token) return false;

        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$token}",
                'Content-Type'  => 'application/json',
            ])->post($url, $payload);

            if ($response->successful()) {
                return true;
            }

            Log::error('FCM v1 send failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('FCM v1 send exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Lấy OAuth2 access token từ service account credentials.
     * Dùng JWT → Google OAuth2 token endpoint, không cần google/auth package.
     */
    private function getAccessToken(): ?string
    {
        return Cache::remember('fcm_access_token', 3500, function () {
            if (!file_exists($this->credentialsPath)) {
                Log::warning('Firebase credentials file not found: ' . $this->credentialsPath);
                return null;
            }

            try {
                $creds = json_decode(file_get_contents($this->credentialsPath), true);

                $now = time();
                $jwt = $this->createJwt([
                    'iss'   => $creds['client_email'],
                    'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                    'aud'   => $creds['token_uri'],
                    'iat'   => $now,
                    'exp'   => $now + 3600,
                ], $creds['private_key']);

                $response = Http::asForm()->post($creds['token_uri'], [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion'  => $jwt,
                ]);

                if ($response->successful()) {
                    return $response->json('access_token');
                }

                Log::error('Firebase OAuth2 token failed', ['body' => $response->body()]);
                return null;
            } catch (\Exception $e) {
                Log::error('Firebase auth failed', ['error' => $e->getMessage()]);
                return null;
            }
        });
    }

    /**
     * Tạo JWT signed bằng RS256 (không cần firebase/php-jwt package).
     */
    private function createJwt(array $payload, string $privateKey): string
    {
        $header = $this->base64url(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $body = $this->base64url(json_encode($payload));

        $key = openssl_pkey_get_private($privateKey);
        openssl_sign("{$header}.{$body}", $signature, $key, OPENSSL_ALGO_SHA256);

        return "{$header}.{$body}." . $this->base64url($signature);
    }

    private function base64url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function stringifyData(array $data): array
    {
        return array_map(fn ($v) => is_string($v) ? $v : json_encode($v), $data);
    }
}
