<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ForumRateLimiter
{
    public function handle(Request $request, Closure $next, string $action = 'posts'): Response
    {
        $customer = auth()->guard('customer')->user();
        if (!$customer) {
            return $next($request);
        }

        $limits = config('forum.rate_limits', []);
        $maxAttempts = $limits[$action] ?? 60;
        $key = "forum:{$action}:{$customer->id}";

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "Bạn đã thao tác quá nhiều. Vui lòng thử lại sau {$seconds} giây.",
                ], 429);
            }

            return back()->with('error', "Bạn đã thao tác quá nhiều. Vui lòng thử lại sau {$seconds} giây.");
        }

        RateLimiter::hit($key, 3600); // decay in 1 hour

        return $next($request);
    }
}
