<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SeoMetaRobots
{
    public function handle(Request $request, Closure $next)
    {
        // 301 redirect index.php URLs to clean URLs
        if ($request->is('index.php/*') || $request->is('index.php')) {
            $cleanUrl = str_replace('/index.php', '', $request->getRequestUri());
            return redirect($cleanUrl ?: '/', 301);
        }

        // 301 redirect www → non-www
        if (str_starts_with($request->getHost(), 'www.')) {
            $url = $request->getScheme() . '://' . substr($request->getHost(), 4) . $request->getRequestUri();
            return redirect($url, 301);
        }

        // 301 redirect junk query params (legacy Joomla, ?$, etc.)
        $junkParams = ['option', 'Itemid', 'lang'];
        if ($request->hasAny($junkParams) || $request->getQueryString() === '$') {
            return redirect($request->url(), 301);
        }

        $response = $next($request);

        // Noindex auth/admin/seller pages
        if ($request->is('auth/*', 'admin/*', 'seller/*', 'checkout/*', 'profile/*')) {
            $this->addHeader($response, 'noindex, nofollow');
        }

        // Noindex paginated pages
        if ($request->has('page') && $request->get('page') > 1) {
            $this->addHeader($response, 'noindex, follow');
        }

        // Noindex search/filter results
        if ($request->has('keyword') || $request->has('sort') || $request->has('order')) {
            $this->addHeader($response, 'noindex, follow');
        }

        // Noindex API-like paths that shouldn't be indexed
        if ($request->is('api/*', 'track-impression')) {
            $this->addHeader($response, 'noindex, nofollow');
        }

        return $response;
    }

    private function addHeader($response, string $content): void
    {
        if (method_exists($response, 'header')) {
            $response->header('X-Robots-Tag', $content);
        }
    }
}
