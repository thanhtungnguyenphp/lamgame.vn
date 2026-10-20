<?php

namespace App\Http\Middleware;

use App\Services\FirebaseAccountLookupUnavailable;
use App\Services\FirebaseAuthService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SharingFirebaseAuth
{
    public function __construct(private FirebaseAuthService $firebase) {}

    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        if (! $token) {
            return $this->error('unauthorized', 401);
        }

        try {
            $claims = $this->firebase->verifyIdTokenClaims(
                $token,
                (bool) config('sharing.require_account_lookup'),
            );
            if (! $claims['email_verified']) {
                return $this->error('email_unverified', 403);
            }

            $request->attributes->set('sharing.auth', $claims);

            return $next($request);
        } catch (FirebaseAccountLookupUnavailable $e) {
            // Do not log the bearer token or Firebase claims. This is actionable
            // for operations and tells the mobile client that retry is appropriate.
            Log::warning('Sharing Firebase account lookup unavailable', [
                'route'  => $request->route()?->getName(),
                'reason' => $e->getMessage(),
            ]);

            return $this->error('auth_verification_unavailable', 503);
        } catch (\Throwable) {
            return $this->error('unauthorized', 401);
        }
    }

    private function error(string $code, int $status)
    {
        return response()->json(['status' => 'error', 'error' => ['code' => $code]], $status);
    }
}
