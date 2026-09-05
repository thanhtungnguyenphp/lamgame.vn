<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SeoMetaRobots
{
    /**
     * Exact ghost URLs that return HTTP 200 with empty/homepage content.
     * Confirmed via production testing — these hit Bagisto's fallback route.
     * 
     * IMPORTANT: Only add URLs here that are NOT in routes/redirects.php
     * (redirects.php routes won't execute if middleware aborts first)
     */
    private array $soft404Urls = [
        'tutorials',
        'guides',
        'source-code',
        'mini-games',
        'reviews',
        'vote',
        'the-sims-5',
        'the-sims-5-preview',
        'mini-game-m7',
        'review-stats',
    ];

    /**
     * Ghost blog slugs that return 200 instead of 404.
     * These match /blog/{slug} route but the blog post doesn't exist in DB
     * OR they hit the Bagisto fallback and render homepage.
     */
    private array $ghostBlogSlugs = [
        'esports',
        'dota-2',
        'free-fire-tips',
        'cs2-map-callouts',
        'review-game',
        'pcg-framework-tutorial',
        'pubg-mobile-guide',
    ];

    /**
     * Ghost tag slugs that don't exist — redirect to /blog
     */
    private array $emptyTagSlugs = [
        'ti-2026',
        'esports',
        'dota-2',
        'the-international',
        'gaming-gear',
    ];

    public function handle(Request $request, Closure $next)
    {
        // === REDIRECT RULES (before route resolution) ===

        // 301 redirect port 2083 (cPanel) URLs to clean URLs
        $port = $request->getPort();
        if ($port === 2083 || str_contains($request->getHost(), ':2083')) {
            $cleanUrl = 'https://' . preg_replace('/:2083$/', '', $request->getHost()) . $request->getRequestUri();
            return redirect($cleanUrl, 301);
        }

        // 301 redirect index.php URLs to clean URLs
        if ($request->is('index.php/*') || $request->is('index.php')) {
            $cleanUrl = str_replace('/index.php', '', $request->getRequestUri());
            return redirect($cleanUrl ?: '/', 301);
        }

        // 301 redirect www → non-www
        if (str_starts_with($request->getHost(), 'www.')) {
            $url = 'https://' . substr($request->getHost(), 4) . $request->getRequestUri();
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

        // === SOFT 404 FIXES ===
        $path = trim($request->path(), '/');

        // Consolidate the archived duplicate into the reviewed canonical article.
        $blogRedirects = [
            'blog/dirty-bomb-game-fps-mien-phi-hay-nhat-tu-splash-damage'
                => '/blog/dirty-bomb-danh-gia-chi-tiet-game-fps-mien-phi',
        ];
        if (isset($blogRedirects[$path])) {
            return redirect($blogRedirects[$path], 301);
        }

        // Fix /index.html and /index → redirect to homepage (not 404)
        if ($path === 'index.html' || $path === 'index') {
            return redirect('/', 301);
        }

        // Fix /en/ → redirect to homepage
        if ($path === 'en' || $path === 'en/') {
            return redirect('/', 301);
        }

        // Landing page that doesn't exist → redirect to blog
        if ($path === 'p/meta-mlbb-m7-2026') {
            return redirect('/blog', 301);
        }

        // Khoa-hoc pages → redirect to blog tags (thin content, better to redirect)
        $khoaHocRedirects = [
            'khoa-hoc/unreal'      => '/blog?tag=unreal-engine',
            'khoa-hoc/csharp'      => '/blog?tag=csharp',
            'khoa-hoc/game-design' => '/blog?tag=game-design',
            'khoa-hoc/mobile'      => '/blog?tag=mobile-game',
            'khoa-hoc/3d-game'     => '/blog?tag=3d-game',
            'khoa-hoc/2d-game'     => '/blog?tag=2d-game',
        ];
        if (isset($khoaHocRedirects[$path])) {
            return redirect($khoaHocRedirects[$path], 301);
        }

        // Exact match ghost URLs → proper 404
        if (in_array($path, $this->soft404Urls)) {
            abort(404);
        }

        // game-reviews/* sub-paths → 404
        if (str_starts_with($path, 'game-reviews/')) {
            abort(404);
        }

        // category/* → redirect to /blog (ghost URLs from old site)
        if (str_starts_with($path, 'category/')) {
            return redirect('/blog', 301);
        }

        // Ghost blog slugs → 404
        if (str_starts_with($path, 'blog/')) {
            $slug = substr($path, 5);
            if (in_array($slug, $this->ghostBlogSlugs)) {
                abort(404);
            }
        }

        // Ghost /tag/* and /tags/* slugs → redirect to /blog
        if (preg_match('#^tags?/(.+)$#', $path, $matches)) {
            $tagSlug = $matches[1];
            if (in_array($tagSlug, $this->emptyTagSlugs)) {
                return redirect('/blog', 301);
            }
            // All other /tag/* paths that don't match forum routes → 404
            // (they hit Bagisto fallback otherwise)
            if (!str_starts_with($path, 'forum/')) {
                abort(404);
            }
        }

        // === PROCESS REQUEST ===
        $response = $next($request);

        // === POST-RESPONSE HEADERS ===

        // Noindex auth/admin/seller/checkout/profile pages
        if ($request->is('auth/*', 'admin/*', 'seller/*', 'checkout/*', 'profile/*')) {
            $this->addHeader($response, 'noindex, nofollow');
        }

        // Noindex paginated pages (page > 1)
        if ($request->has('page') && $request->get('page') > 1) {
            $this->addHeader($response, 'noindex, follow');
        }

        // Noindex search/filter/sort results
        if ($request->has('keyword') || $request->has('sort') || $request->has('order')) {
            $this->addHeader($response, 'noindex, follow');
        }

        // Noindex API-like paths
        if ($request->is('api/*', 'api-sport/*', 'track-impression')) {
            $this->addHeader($response, 'noindex, nofollow');
        }

        // Noindex blog tag/category pages with thin content (< 5 posts)
        // Also noindex if tag/category has 0 posts (empty page)
        if ($request->is('blog') && ($request->has('tag') || $request->has('category'))) {
            $this->noindexThinListingPages($response);
        }

        return $response;
    }

    /**
     * Noindex blog tag/category listing pages with few posts.
     * - 0 posts → noindex (handled by controller abort(404) for non-existent tags)
     * - < 5 posts → noindex (thin content)
     */
    private function noindexThinListingPages($response): void
    {
        try {
            $original = $response->getOriginalContent();
            if ($original && method_exists($original, 'getData')) {
                $viewData = $original->getData();
                $blogs = $viewData['blogs'] ?? null;
                if ($blogs && method_exists($blogs, 'total') && $blogs->total() < 5) {
                    $this->addHeader($response, 'noindex, follow');
                }
            }
        } catch (\Throwable) {
            // Don't break the response if we can't read view data
        }
    }

    private function addHeader($response, string $content): void
    {
        if (method_exists($response, 'header')) {
            $response->header('X-Robots-Tag', $content);
        }
    }
}
