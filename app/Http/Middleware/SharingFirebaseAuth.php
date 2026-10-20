<?php

namespace App\Http\Middleware;

use App\Services\FirebaseAuthService;
use Closure;
use Illuminate\Http\Request;

class SharingFirebaseAuth
{
    public function __construct(private FirebaseAuthService $firebase) {}

    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return $this->error('unauthorized', 401);
        }

        try {
            $claims = $this->firebase->verifyIdTokenClaims(
                $token,
                (bool) config('sharing.require_account_lookup'),
            );
            if (!$claims['email_verified']) {
                return $this->error('email_unverified', 403);
            }

            $request->attributes->set('sharing.auth', $claims);
            return $next($request);
        } catch (\Throwable) {
            return $this->error('unauthorized', 401);
        }
    }

    private function error(string $code, int $status)
    {
        return response()->json(['status' => 'error', 'error' => ['code' => $code]], $status);
    }
}
