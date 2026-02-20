<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthentication
{
    /**
     * Ensure the request expects a JSON response.
     * Note: This is NOT authentication - it only validates the Accept header.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->expectsJson()) {
            return response()->json([
                'message' => 'API requires Accept: application/json header.'
            ], 406);
        }

        return $next($request);
    }
}