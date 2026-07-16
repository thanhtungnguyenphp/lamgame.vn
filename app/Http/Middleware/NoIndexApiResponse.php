<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Adds X-Robots-Tag: noindex, nofollow to all API responses.
 * 
 * This is a defense-in-depth measure alongside robots.txt disallow rules.
 * Google can discover API URLs through JavaScript/internal links even if
 * robots.txt blocks them. The X-Robots-Tag header provides an explicit
 * signal to not index these responses.
 */
class NoIndexApiResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Robots-Tag', 'noindex, nofollow');

        return $response;
    }
}
