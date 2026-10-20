<?php

namespace App\Services;

use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class FirebaseAuthService
{
    private const GOOGLE_CERTS_URL = 'https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com';

    /**
     * Verify Firebase ID Token và trả về uid.
     *
     * @throws \Exception
     */
    public function verifyIdToken(string $idToken): string
    {
        return $this->verifyIdTokenClaims($idToken)['uid'];
    }

    /**
     * Verify signature and, for the sharing boundary, Firebase account state.
     * The account lookup catches disabled users and tokens revoked after auth_time.
     * It deliberately fails closed when this check cannot be performed.
     *
     * @return array{uid:string,email:?string,email_verified:bool}
     */
    public function verifyIdTokenClaims(string $idToken, bool $requireAccountLookup = false): array
    {
        $keys = $this->getPublicKeys();
        $projectId = config('services.firebase.project_id') ?: config('firebase.project_id');

        $decoded = JWT::decode($idToken, JWK::parseKeySet($keys));

        // Verify claims
        if ($decoded->aud !== $projectId) {
            throw new \Exception('Invalid audience');
        }
        if ($decoded->iss !== "https://securetoken.google.com/{$projectId}") {
            throw new \Exception('Invalid issuer');
        }
        if ($decoded->exp < time()) {
            throw new \Exception('Token expired');
        }

        if (!isset($decoded->sub) || !is_string($decoded->sub) || $decoded->sub === '') {
            throw new \Exception('Invalid subject');
        }

        $claims = [
            'uid' => $decoded->sub,
            'email' => isset($decoded->email) && is_string($decoded->email) ? $decoded->email : null,
            'email_verified' => (bool) ($decoded->email_verified ?? false),
            'name' => isset($decoded->name) && is_string($decoded->name) ? $decoded->name : null,
        ];

        if ($requireAccountLookup) {
            $this->verifyAccountState($idToken, $decoded);
        }

        return $claims;
    }

    private function verifyAccountState(string $idToken, object $decoded): void
    {
        $apiKey = config('firebase.web.apiKey') ?: env('FIREBASE_WEB_API_KEY');
        if (!$apiKey) {
            throw new \Exception('Firebase account lookup is not configured');
        }

        try {
            $response = Http::timeout(5)->post(
                'https://identitytoolkit.googleapis.com/v1/accounts:lookup?key=' . urlencode($apiKey),
                ['idToken' => $idToken],
            );
            $user = $response->json('users.0');

            if (!$response->successful() || !is_array($user) || ($user['disabled'] ?? false)) {
                throw new \Exception('Firebase account unavailable');
            }

            // validSince is the Firebase token-revocation boundary (seconds since epoch).
            if (isset($user['validSince']) && isset($decoded->auth_time)
                && (int) $decoded->auth_time < (int) $user['validSince']) {
                throw new \Exception('Firebase token revoked');
            }
        } catch (\Exception $e) {
            throw new \Exception('Firebase account lookup failed', previous: $e);
        }
    }

    private function getPublicKeys(): array
    {
        return Cache::remember('firebase:public_keys', 3600, function () {
            $response = Http::get(self::GOOGLE_CERTS_URL);
            $certs = $response->json();

            $keys = [];
            foreach ($certs as $kid => $cert) {
                $keys['keys'][] = [
                    'kty' => 'RSA',
                    'kid' => $kid,
                    'use' => 'sig',
                    'x5c' => [base64_encode(
                        (string) openssl_pkey_get_details(openssl_pkey_get_public($cert))['key']
                    )],
                    ...$this->certToJwk($cert, $kid),
                ];
            }

            return $keys;
        });
    }

    private function certToJwk(string $cert, string $kid): array
    {
        $pubKey = openssl_pkey_get_public($cert);
        $details = openssl_pkey_get_details($pubKey);

        return [
            'kty' => 'RSA',
            'kid' => $kid,
            'n'   => rtrim(strtr(base64_encode($details['rsa']['n']), '+/', '-_'), '='),
            'e'   => rtrim(strtr(base64_encode($details['rsa']['e']), '+/', '-_'), '='),
        ];
    }
}
