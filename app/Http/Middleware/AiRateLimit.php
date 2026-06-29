<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AiRateLimit
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $key = 'ai-tools:' . ($user?->id ?? $request->ip());
        $maxAttempts = config('ai-tools.rate_limit.per_minute', 30);

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($key);

            return response()->json([
                'error'   => 'rate_limited',
                'message' => "Quá nhiều request. Vui lòng đợi {$retryAfter}s.",
                'retry_after' => $retryAfter,
            ], 429);
        }

        RateLimiter::hit($key, 60);

        return $next($request);
    }
}
