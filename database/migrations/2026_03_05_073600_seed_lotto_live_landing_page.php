<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('landing_pages')->where('slug', 'ung-dung-lotto-live')->exists();
        if ($exists) {
            return;
        }

        DB::table('landing_pages')->insert([
            'name'             => 'Ứng dụng Lotto Live',
            'slug'             => 'ung-dung-lotto-live',
            'template'         => 'app-lotto-live',
            'hero_title'       => 'Lotto Live',
            'hero_subtitle'    => 'Xem kết quả xổ số trực tiếp, dự đoán thông minh và thống kê chuyên sâu — tất cả trong một ứng dụng miễn phí.',
            'hero_cta_text'    => null,
            'hero_cta_url'     => null,
            'hero_bg_image'    => null,
            'hero_bg_color'    => null,
            'description'      => null,
            'sections'         => json_encode([
                'app_store_url'    => '#',
                'google_play_url'  => '#',
                'hero_mockup_image' => '/images/lotto-live/mockup-home.png',
                'features' => [
                    [
                        'icon'  => '⚡',
                        'title' => 'Kết quả siêu tốc',
                        'text'  => 'Cập nhật KQXS trực tiếp nhanh nhất, chính xác từ các đài xổ số trên toàn quốc.',
                        'image' => '/images/lotto-live/feature-results.png',
                    ],
                    [
                        'icon'  => '🎯',
                        'title' => 'Dự đoán thông minh',
                        'text'  => 'Phân tích xu hướng, tần suất xuất hiện giúp bạn chọn bộ số may mắn.',
                        'image' => '/images/lotto-live/feature-prediction.png',
                    ],
                    [
                        'icon'  => '📊',
                        'title' => 'Thống kê chuyên sâu',
                        'text'  => 'Biểu đồ trực quan, lô gan, đầu đuôi, bộ số nóng lạnh theo từng đài.',
                        'image' => '/images/lotto-live/feature-stats.png',
                    ],
                ],
                'highlights' => [
                    [
                        'title'       => 'Lưu bộ số may mắn',
                        'description' => 'Không bao giờ quên bộ số may mắn của bạn. Lưu, quản lý và theo dõi kết quả tự động mọi lúc mọi nơi.',
                        'image'       => '/images/lotto-live/highlight-save-number.png',
                    ],
                    [
                        'title'       => 'Xem kết quả trực tiếp',
                        'description' => 'Theo dõi kết quả xổ số phát trực tiếp ngay trên điện thoại. Không bỏ lỡ bất kỳ giải quay nào.',
                        'image'       => '/images/lotto-live/highlight-live.png',
                    ],
                ],
                'steps' => [
                    ['title' => 'Tải ứng dụng',    'text' => 'Miễn phí trên App Store & Google Play'],
                    ['title' => 'Chọn đài xổ số',  'text' => 'Theo dõi đài yêu thích của bạn'],
                    ['title' => 'Nhận kết quả',     'text' => 'Thông báo ngay khi có kết quả mới'],
                ],
                'stats' => [
                    ['value' => '100K+',  'label' => 'Lượt tải'],
                    ['value' => '50K+',   'label' => 'Người dùng hoạt động'],
                    ['value' => '4.8 ⭐', 'label' => 'Đánh giá trung bình'],
                    ['value' => '63',     'label' => 'Đài xổ số hỗ trợ'],
                ],
                'cta_title'    => 'Tải ngay Lotto Live',
                'cta_subtitle' => 'Hoàn toàn miễn phí — Có mặt trên App Store & Google Play',
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'meta_title'       => 'Lotto Live - Ứng dụng xem KQXS trực tiếp, dự đoán & thống kê xổ số',
            'meta_description' => 'Tải Lotto Live miễn phí — xem kết quả xổ số trực tiếp, dự đoán thông minh, thống kê chuyên sâu 63 đài. Lưu bộ số may mắn, nhận thông báo tức thì.',
            'meta_keywords'    => 'lotto live, kết quả xổ số, KQXS trực tiếp, xổ số miền bắc, xổ số miền nam, dự đoán xổ số, thống kê xổ số, ứng dụng xổ số',
            'og_image'         => null,
            'status'           => true,
            'start_at'         => null,
            'end_at'           => null,
            'author'           => 'LamGame',
            'author_id'        => 1,
            'views'            => 0,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('landing_pages')->where('slug', 'ung-dung-lotto-live')->delete();
    }
};
