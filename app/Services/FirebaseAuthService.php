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
        $keys = $this->getPublicKeys();
        $projectId = config('services.firebase.project_id');

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

        return $decoded->sub;
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
