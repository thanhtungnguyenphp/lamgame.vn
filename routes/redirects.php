<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SEO Redirects — Legacy/orphan URLs → relevant existing pages
|--------------------------------------------------------------------------
| Fix "Crawled - currently not indexed" by redirecting dead URLs
| to proper pages with content.
*/

// Legacy category-like URLs → Blog categories
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

// Legacy .html URLs that .htaccess didn't catch (double encoding)
Route::permanentRedirect('tong-hop-cac-cau-lenh-trong-minecraft-can-thiet-nhat', '/blog');
Route::permanentRedirect('tong-hop-cac-nhan-vat-trong-cf-khong-thieu-1-ai', '/blog');

// Khoa hoc pages exist with content — no redirect needed
// If they're not indexed, issue is thin content, not missing pages
