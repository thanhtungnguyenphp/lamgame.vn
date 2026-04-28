<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForumHoneypot
{
    public function handle(Request $request, Closure $next): Response
    {
        $field = config('forum.honeypot_field', 'website_url');

        if ($request->isMethod('POST') && $request->filled($field)) {
            // Bot detected — silently redirect back as if success
            if ($request->expectsJson()) {
                return response()->json(['message' => 'OK'], 200);
            }
            return back()->with('success', 'Đã gửi thành công.');
        }

        return $next($request);
    }
}
