<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    public function up(): void
    {
        // Lấy max id hiện tại
        $maxId = DB::table('cms_pages')->max('id') ?? 0;
        $pageId = $maxId + 1;

        DB::table('cms_pages')->insert([
            'id'         => $pageId,
            'layout'     => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('cms_page_translations')->insert([
            [
                'locale'           => 'vi',
                'cms_page_id'      => $pageId,
                'url_key'          => 'ai-tools',
                'page_title'       => 'AI Tools cho Game Developer',
                'html_content'     => '<div class="static-container"><p>Trang này đã chuyển sang <a href="/ai-tools">AI Tools</a>.</p></div>',
                'meta_title'       => 'AI Tools cho Game Developer - Làm Game',
                'meta_description' => 'Công cụ AI hỗ trợ lập trình game: Code Generate, Debug, Unit Test, Asset Generate. Gói Free, Pro $9/tháng, Business $29/tháng.',
                'meta_keywords'    => 'ai tools, game developer, code generate, debug, unit test, lamgame',
            ],
            [
                'locale'           => 'en',
                'cms_page_id'      => $pageId,
                'url_key'          => 'ai-tools',
                'page_title'       => 'AI Tools for Game Developers',
                'html_content'     => '<div class="static-container"><p>This page has moved to <a href="/ai-tools">AI Tools</a>.</p></div>',
                'meta_title'       => 'AI Tools for Game Developers - LamGame',
                'meta_description' => 'AI-powered tools for game development: Code Generate, Debug, Unit Test, Asset Generate. Free, Pro $9/mo, Business $29/mo.',
                'meta_keywords'    => 'ai tools, game developer, code generate, debug, unit test, lamgame',
            ],
        ]);
    }

    public function down(): void
    {
        $page = DB::table('cms_page_translations')->where('url_key', 'ai-tools')->first();
        if ($page) {
            DB::table('cms_page_translations')->where('cms_page_id', $page->cms_page_id)->delete();
            DB::table('cms_pages')->where('id', $page->cms_page_id)->delete();
        }
    }
};
