<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SEO Redirects — Legacy/orphan URLs → relevant existing pages
|--------------------------------------------------------------------------
| Fix "Crawled - currently not indexed" by redirecting dead URLs
| to proper pages with content.
|
| Updated: 2026-07-16 10:20 — after production testing
*/

// ==========================================================================
// GROUP 1: Legacy category-like URLs → Blog categories
// ==========================================================================
Route::permanentRedirect('lol-guides', '/blog?tag=unity');
Route::permanentRedirect('mobile-game', '/blog?category=mobile-game');
Route::permanentRedirect('sports-gaming', '/the-thao');
Route::permanentRedirect('streamers', '/cong-dong');
Route::permanentRedirect('unity-tutorials', '/blog?category=unity-development');
Route::permanentRedirect('game-development', '/blog?category=unity-development');
Route::permanentRedirect('tournaments', '/the-thao/lich-thi-dau');
Route::permanentRedirect('tin-tuc-game', '/blog');
Route::permanentRedirect('community', '/cong-dong');
Route::permanentRedirect('esports-viet-nam', '/blog?category=esports');

// ==========================================================================
// GROUP 2: Legacy .html URLs that .htaccess didn't catch (double encoding)
// ==========================================================================
Route::permanentRedirect('tong-hop-cac-cau-lenh-trong-minecraft-can-thiet-nhat', '/blog');
Route::permanentRedirect('tong-hop-cac-nhan-vat-trong-cf-khong-thieu-1-ai', '/blog');

// ==========================================================================
// GROUP 3: Ghost URLs from old site versions (GSC "Crawled - not indexed")
// Confirmed returning HTTP 200 on production — need explicit redirects
// ==========================================================================

// Game category pages → blog
Route::permanentRedirect('game-reviews', '/blog');
Route::permanentRedirect('category/game-reviews', '/blog');

// Game-specific ghost URLs → blog with relevant tag
Route::permanentRedirect('cs2', '/blog?tag=fps');
Route::permanentRedirect('valorant', '/blog?tag=fps');
Route::permanentRedirect('valorant-competitive', '/blog?tag=fps');
Route::permanentRedirect('lol-news', '/blog?tag=lol');
Route::permanentRedirect('lol-top-lane-guide', '/blog');

// Sports → /the-thao
Route::permanentRedirect('vietnam-football', '/the-thao');

// ==========================================================================
// GROUP 4: Khoa hoc (courses) pages → blog tags
// Production test confirmed: these return HTTP 200 with empty/thin content.
// Redirect to blog tags that have actual related articles.
// ==========================================================================
Route::permanentRedirect('khoa-hoc/unreal', '/blog?tag=unreal-engine');
Route::permanentRedirect('khoa-hoc/csharp', '/blog?tag=csharp');
Route::permanentRedirect('khoa-hoc/game-design', '/blog?tag=game-design');
Route::permanentRedirect('khoa-hoc/mobile', '/blog?tag=mobile-game');
Route::permanentRedirect('khoa-hoc/3d-game', '/blog?tag=3d-game');
Route::permanentRedirect('khoa-hoc/2d-game', '/blog?tag=2d-game');

// ==========================================================================
// GROUP 5: Landing pages that don't exist
// ==========================================================================
Route::permanentRedirect('p/meta-mlbb-m7-2026', '/blog');

// ==========================================================================
// GROUP 6: Forum legacy ID-based URLs
// Handled via ForumPost::resolveRouteBinding() in model.
// Numeric IDs → 301 redirect to slug-based URL or 410 Gone.
// ForumTag::resolveRouteBinding() handles suffix dedup (unity-1 → unity).
// ==========================================================================
