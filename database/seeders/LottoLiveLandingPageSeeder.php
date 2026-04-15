<?php

namespace Database\Seeders;

use App\Models\LandingPage;
use Illuminate\Database\Seeder;

class LottoLiveLandingPageSeeder extends Seeder
{
    public function run(): void
    {
        LandingPage::updateOrCreate(
            ['slug' => 'lottolive'],
            [
                'name'             => 'Lotto Live — Tra cứu kết quả xổ số',
                'template'         => 'app-lotto-live',
                'hero_title'       => 'Tra cứu kết quả xổ số nhanh nhất Việt Nam',
                'hero_subtitle'    => 'KQXS 3 miền Bắc Trung Nam, Vietlot. Chụp vé dò số tự động bằng camera, thống kê số nóng lạnh, sổ mơ giải mã giấc mơ.',
                'hero_cta_text'    => 'Tải miễn phí',
                'hero_cta_url'     => '#download',
                'meta_title'       => 'Lotto Live — Tra cứu kết quả xổ số 3 miền, Vietlot | Chụp vé dò số tự động',
                'meta_description' => 'Ứng dụng tra cứu kết quả xổ số kiến thiết 3 miền Bắc Trung Nam, Vietlot. Chụp vé dò số tự động bằng camera, thống kê số nóng lạnh, sổ mơ. Miễn phí!',
                'meta_keywords'    => 'xổ số, kết quả xổ số, KQXS, XSMB, XSMN, XSMT, dò vé số, vietlot, chụp vé số, thống kê xổ số, sổ mơ, lotto live',
                'status'           => true,
                'sections'         => [
                    // Store URLs
                    'google_play_url'   => 'https://play.google.com/store/apps/details?id=vn.lamgame.lottolive',
                    'app_store_url'     => null,
                    'hero_mockup_image' => '/lp/lottolive/screenshots/01_home_top.png',

                    // Features
                    'features' => [
                        ['icon' => '📋', 'title' => 'Kết quả xổ số 3 miền',   'text' => 'XSMB, XSMN, XSMT cập nhật ngay sau giờ quay. Tự động refresh khi đến giờ quay số.'],
                        ['icon' => '🎰', 'title' => 'Vietlot đầy đủ',         'text' => 'Mega 6/45, Power 6/55, Max 3D, Max 3D Pro, Keno. Jackpot, cơ cấu giải, thống kê Keno.'],
                        ['icon' => '📷', 'title' => 'Chụp vé dò số tự động',   'text' => 'Chụp ảnh vé số → nhận diện số, đài, ngày bằng AI. Dò giải ngay, không cần nhập thủ công.'],
                        ['icon' => '🔍', 'title' => 'Dò số siêu nhanh',        'text' => 'Nhập 2–6 số cuối, biết ngay trúng/trượt. Số trùng highlight rõ ràng trong bảng kết quả.'],
                        ['icon' => '📊', 'title' => 'Thống kê nâng cao',        'text' => 'Số nóng, số lạnh, cầu lô, đầu đuôi. Gợi ý số Đặc Biệt cho ngày mai theo từng đài.'],
                        ['icon' => '🌙', 'title' => 'Sổ mơ giải mã giấc mơ',  'text' => 'Tra cứu sổ mơ, tìm con số may mắn từ giấc mơ đêm qua. Cơ sở dữ liệu phong phú.'],
                        ['icon' => '🔔', 'title' => 'Thông báo kết quả',       'text' => 'Push notification KQXS hàng ngày. Thông báo ngay khi vé trúng giải. Chọn đài nhận thông báo.'],
                        ['icon' => '📱', 'title' => 'Widget trang chủ',         'text' => 'Xem giải Đặc Biệt + countdown ngay trên màn hình chính Android. Không cần mở app.'],
                        ['icon' => '💾', 'title' => 'Ghi số & đồng bộ',        'text' => 'Lưu số yêu thích, tự động dò khi có kết quả. Đồng bộ cloud, không mất dữ liệu khi đổi máy.'],
                    ],

                    // Highlights (Z-pattern sections)
                    'highlights' => [
                        [
                            'title'       => 'Chụp vé số — Dò giải tự động',
                            'description' => 'Không cần nhập số thủ công. Chỉ cần chụp ảnh vé số, ứng dụng sẽ tự nhận diện và dò giải ngay lập tức. Nhận diện số, tên đài, ngày quay tự động. Chụp nhiều vé liên tiếp. Hoạt động offline.',
                            'image'       => '/lp/lottolive/screenshots/06_save_number.png',
                        ],
                        [
                            'title'       => 'Phân tích số — Đưa ra quyết định tốt hơn',
                            'description' => 'Thống kê chi tiết giúp bạn nắm bắt xu hướng kết quả xổ số. Số nóng, số lạnh theo tần suất. Cầu lô, thống kê đầu đuôi theo đài. Gợi ý số Đặc Biệt cho ngày mai.',
                            'image'       => '/lp/lottolive/screenshots/02_home_bottom.png',
                        ],
                    ],

                    // How it works
                    'steps' => [
                        ['title' => 'Tải app',       'text' => 'Tải Lotto Live miễn phí trên Google Play.'],
                        ['title' => 'Chọn đài',      'text' => 'Chọn miền Bắc, Trung, Nam hoặc Vietlot.'],
                        ['title' => 'Xem kết quả',   'text' => 'Kết quả cập nhật tự động ngay sau giờ quay.'],
                        ['title' => 'Dò vé',         'text' => 'Chụp vé hoặc nhập số để dò giải tức thì.'],
                    ],

                    // Stats
                    'stats' => [
                        ['value' => '3 miền',  'label' => 'Bắc · Trung · Nam'],
                        ['value' => '63 đài',  'label' => 'Xổ số kiến thiết'],
                        ['value' => '5 game',  'label' => 'Vietlot đầy đủ'],
                        ['value' => '0đ',      'label' => 'Hoàn toàn miễn phí'],
                    ],

                    // Footer CTA
                    'cta_title'    => 'Tải ngay Lotto Live',
                    'cta_subtitle' => 'Tra cứu xổ số nhanh, dò vé tự động, thống kê thông minh — Hoàn toàn miễn phí!',

                    // Screenshots gallery
                    'screenshots' => [
                        ['image' => '/lp/lottolive/screenshots/01_home_top.png',        'alt' => 'Trang chủ Lotto Live'],
                        ['image' => '/lp/lottolive/screenshots/02_home_bottom.png',     'alt' => 'Thống kê số nóng lạnh'],
                        ['image' => '/lp/lottolive/screenshots/03_results_south.png',   'alt' => 'Kết quả xổ số miền Nam'],
                        ['image' => '/lp/lottolive/screenshots/04_results_more.png',    'alt' => 'Kết quả xổ số chi tiết'],
                        ['image' => '/lp/lottolive/screenshots/05_results_central.png', 'alt' => 'Kết quả xổ số miền Trung'],
                        ['image' => '/lp/lottolive/screenshots/06_save_number.png',     'alt' => 'Ghi số dò vé'],
                    ],

                    // Safety tags
                    'safety_tags' => [
                        '🔒 Không mua bán vé số',
                        '🚫 Không cờ bạc, đặt cược',
                        '🛡️ Bảo mật thông tin',
                        '✅ Tuân thủ pháp luật VN',
                        '📱 Dữ liệu lưu trên thiết bị',
                        '🌙 Hỗ trợ Dark Mode',
                    ],
                ],
            ]
        );
    }
}
