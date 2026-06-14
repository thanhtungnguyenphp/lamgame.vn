<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SeoMetaRobots
{
    public function handle(Request $request, Closure $next)
    {
        // 301 redirect port 2083 (cPanel) URLs to clean URLs
        $port = $request->getPort();
        if ($port === 2083 || str_contains($request->getHost(), ':2083')) {
            $cleanUrl = $request->getScheme() . '://' . preg_replace('/:2083$/', '', $request->getHost()) . $request->getRequestUri();
            return redirect($cleanUrl, 301);
        }

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

        // 301 redirect ?page=1 to clean URL (duplicate of non-paginated)
        if ($request->get('page') === '1') {
            $cleanParams = $request->except('page');
            $cleanUrl = $request->url() . ($cleanParams ? '?' . http_build_query($cleanParams) : '');
            return redirect($cleanUrl, 301);
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
