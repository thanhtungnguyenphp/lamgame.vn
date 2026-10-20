<?php

namespace App\Console\Commands;

use App\Services\FirebaseAuthService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CheckSharingFirebaseCommand extends Command
{
    protected $signature = 'sharing:check-firebase';

    protected $description = 'Check Firebase sharing authentication configuration and connectivity without exposing secrets.';

    public function handle(): int
    {
        $projectId = config('services.firebase.project_id') ?: config('firebase.project_id');
        $apiKey = config('firebase.identity_toolkit_api_key');
        $ok = true;

        $this->line('Firebase sharing authentication diagnostics');
        $this->line('Project ID: '.($projectId ?: '<missing>'));

        if (! $projectId) {
            $this->error('FIREBASE_PROJECT_ID is missing from the effective configuration.');
            $ok = false;
        }

        if (! $apiKey) {
            $this->error('FIREBASE_IDENTITY_TOOLKIT_API_KEY (or FIREBASE_WEB_API_KEY fallback) is missing from the effective configuration.');

            return self::FAILURE;
        }

        try {
            $certs = Http::acceptJson()->timeout(5)->get(FirebaseAuthService::GOOGLE_CERTS_URL);
            if (! $certs->successful() || ! is_array($certs->json()) || $certs->json() === []) {
                $this->error('Cannot fetch Firebase public signing certificates.');
                $ok = false;
            } else {
                $this->info('Public signing certificates: reachable');
            }
        } catch (\Throwable) {
            $this->error('Cannot reach Firebase public signing certificates.');
            $ok = false;
        }

        try {
            $response = Http::acceptJson()->timeout(5)->post(
                'https://identitytoolkit.googleapis.com/v1/accounts:lookup?key='.urlencode($apiKey),
                ['idToken' => 'local-diagnostic-invalid-token'],
            );
            $message = (string) $response->json('error.message', '');

            // The deliberately invalid token must be rejected by a configured,
            // reachable API key. Any other result points to key restrictions,
            // a wrong project, or unavailable outbound networking.
            if ($response->status() === 400 && $message === 'INVALID_ID_TOKEN') {
                $this->info('Identity Toolkit API key and outbound access: reachable');
            } else {
                $this->error("Identity Toolkit check failed (HTTP {$response->status()}, {$message}).");
                $ok = false;
            }
        } catch (\Throwable) {
            $this->error('Cannot reach Identity Toolkit.');
            $ok = false;
        }

        return $ok ? self::SUCCESS : self::FAILURE;
    }
}
