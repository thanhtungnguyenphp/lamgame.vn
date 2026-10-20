<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class SharingRateLimit
{
    public function handle(Request $request, Closure $next, int $maxAttempts = 30)
    {
        $auth = $request->attributes->get('sharing.auth', []);
        $scopes = [
            'uid:' . ($auth['uid'] ?? 'anonymous'),
            'ip:' . ($request->ip() ?? 'unknown'),
        ];
        // Flutter sends this header after device registration. It is an anti-abuse
        // dimension only, never an authorization credential.
        if ($installationId = $request->header('X-Installation-Id')) {
            $scopes[] = 'installation:' . substr($installationId, 0, 128);
        }

        foreach ($scopes as $scope) {
            $key = 'sharing:' . $request->route()?->getName() . ':' . $scope;
            if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
                return response()->json(['status' => 'error', 'error' => ['code' => 'rate_limited']], 429)
                    ->header('Retry-After', RateLimiter::availableIn($key));
            }
        }
        foreach ($scopes as $scope) {
            RateLimiter::hit('sharing:' . $request->route()?->getName() . ':' . $scope, 60);
        }

        return $next($request);
    }
}
