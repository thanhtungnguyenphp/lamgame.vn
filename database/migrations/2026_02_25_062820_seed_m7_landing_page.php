<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('landing_pages')->where('slug', 'meta-mlbb-m7-2026')->exists();
        if ($exists) {
            return;
        }

        DB::table('landing_pages')->insert([
            'name'             => 'MLBB M7 World Championship 2026 - Dự đoán & Nhận quà',
            'slug'             => 'meta-mlbb-m7-2026',
            'template'         => 'event-countdown',
            'hero_title'       => 'M7 WORLD CHAMPIONSHIP 2026',
            'hero_subtitle'    => 'Giải vô địch thế giới Mobile Legends lớn nhất lịch sử. Dự đoán đội vô địch - Nhận quà cực hot!',
            'hero_cta_text'    => '🎮 Dự đoán ngay & Nhận quà',
            'hero_cta_url'     => 'https://lamgame.vn/mini-game-m7',
            'hero_bg_image'    => null,
            'hero_bg_color'    => '#0a0e27',
            'description'      => '<h2 style="text-align:center;margin-bottom:1rem">Về M7 World Championship 2026</h2><p>M7 World Championship 2026 là giải vô địch thế giới Mobile Legends: Bang Bang lớn nhất trong lịch sử, với tổng giải thưởng lên đến <strong>1.000.000 USD</strong>. Diễn ra tại <strong>Jakarta, Indonesia</strong> từ ngày 03/03 đến 25/03/2026.</p><p>Giải đấu năm nay áp dụng hệ thống <strong>Swiss Stage</strong> hoàn toàn mới, loại bỏ các trận đấu thủ tục và buộc các đội phải tung hết bài ngay từ những ngày đầu tiên.</p><h3>Tại sao M7 2026 đặc biệt?</h3><ul><li>🏆 Giải thưởng cao nhất lịch sử MLBB</li><li>🌏 Lần đầu tiên khu vực Trung Quốc và EECA hội nhập sâu</li><li>⚔️ Format Swiss Stage mới - mỗi trận đều là trận sống còn</li><li>🇮🇩 Tổ chức tại Indonesia - thánh địa của cộng đồng MLBB</li></ul>',
            'sections'         => json_encode([
                'stats' => [
                    'bg'    => true,
                    'type'  => 'cards',
                    'title' => '🏆 M7 2026 - Những con số ấn tượng',
                    'items' => [
                        ['icon' => '🌍', 'title' => '10+ Đội tuyển', 'text' => 'Đại diện từ Philippines, Indonesia, Malaysia, Việt Nam, Trung Quốc, EECA, MENA'],
                        ['icon' => '💰', 'title' => '$1,000,000 USD', 'text' => 'Tổng giá trị giải thưởng lớn nhất lịch sử MLBB'],
                        ['icon' => '📍', 'title' => 'Jakarta, Indonesia', 'text' => 'Thánh địa Mobile Legends - Bầu không khí cuồng nhiệt nhất'],
                        ['icon' => '⚔️', 'title' => 'Swiss Stage', 'text' => 'Format thi đấu mới - Không còn trận đấu thủ tục'],
                    ],
                ],
                'teams' => [
                    'bg'      => false,
                    'type'    => 'text',
                    'title'   => '🔥 Danh sách đội tuyển M7 2026',
                    'content' => '<table style="width:100%;border-collapse:collapse;margin:1rem 0"><thead><tr style="background:#6a4c93;color:#fff"><th style="padding:12px 16px;text-align:left">Khu vực</th><th style="padding:12px 16px;text-align:left">Đội tuyển</th><th style="padding:12px 16px;text-align:left">Vị thế</th></tr></thead><tbody><tr style="background:#f8f6fb"><td style="padding:10px 16px">🇵🇭 Philippines</td><td style="padding:10px 16px"><strong>Aurora Gaming PH, Team Liquid PH</strong></td><td style="padding:10px 16px">Ứng viên vô địch #1</td></tr><tr><td style="padding:10px 16px">🇮🇩 Indonesia</td><td style="padding:10px 16px"><strong>ONIC Esports, Alter Ego</strong></td><td style="padding:10px 16px">Chủ nhà - Đối trọng lớn nhất</td></tr><tr style="background:#f8f6fb"><td style="padding:10px 16px">🇲🇾 Malaysia</td><td style="padding:10px 16px"><strong>Selangor Red Giants, CG Esports</strong></td><td style="padding:10px 16px">Kẻ thách thức</td></tr><tr><td style="padding:10px 16px">🇻🇳 Việt Nam</td><td style="padding:10px 16px"><strong>RLG SE</strong></td><td style="padding:10px 16px">Niềm hy vọng Việt Nam</td></tr><tr style="background:#f8f6fb"><td style="padding:10px 16px">🌏 Khu vực khác</td><td style="padding:10px 16px"><strong>Team Spirit, Team Falcons, DianFengYaoGuai</strong></td><td style="padding:10px 16px">Ngựa ô giải đấu</td></tr></tbody></table>',
                ],
                'blog_link' => [
                    'bg'      => true,
                    'type'    => 'text',
                    'title'   => '📖 Phân tích chuyên sâu',
                    'content' => '<div style="text-align:center;padding:1rem"><p style="font-size:1.1rem;margin-bottom:1.5rem">Đọc bài phân tích chi tiết về đội hình, phong độ và dự đoán kết quả M7 2026 từ đội ngũ chuyên gia LamGame.</p><a href="/blog/mlbb-m7-2026-teams-danh-sach-doi-tuyen-va-ung-vien-vo-dich" style="display:inline-block;padding:0.75rem 2rem;background:#6a4c93;color:#fff;border-radius:50px;text-decoration:none;font-weight:700">📊 Xem bài phân tích đầy đủ →</a></div>',
                ],
                'prediction' => [
                    'type'     => 'cta',
                    'title'    => '🎯 Bạn dự đoán đội nào vô địch M7 2026?',
                    'content'  => '<p style="font-size:1.1rem">Philippines tiếp tục thống trị? Indonesia đòi lại ngôi vương ngay sân nhà? Hay một cú sốc từ khu vực mới nổi?</p><p style="font-size:1rem;opacity:0.9;margin-top:0.5rem">Dự đoán đúng → Nhận quà từ LamGame! 🎁</p>',
                    'cta_url'  => 'https://lamgame.vn/mini-game-m7',
                    'cta_text' => '🏆 Tham gia Mini Game Dự đoán M7',
                ],
                'meta_heroes' => [
                    'bg'    => true,
                    'type'  => 'cards',
                    'title' => '⚡ Meta Hero M7 2026 - Ai đang thống trị?',
                    'items' => [
                        ['icon' => '🗡️', 'title' => 'Nolan', 'text' => 'Sát thủ #1 meta hiện tại. Xuyên giáp cực mạnh sau patch mới.'],
                        ['icon' => '💨', 'title' => 'Joy', 'text' => 'Cơ động bậc nhất. Khả năng roam và gank không đối thủ.'],
                        ['icon' => '🦇', 'title' => 'Helcurt', 'text' => 'Bóng tối bao trùm. Kiểm soát tầm nhìn đối phương hoàn hảo.'],
                        ['icon' => '🛡️', 'title' => 'Chiến thuật 1-3-1', 'text' => 'Đảo đường liên tục, ép trụ sớm. Không còn meta "nuôi rùa".'],
                    ],
                ],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'meta_title'       => 'M7 World Championship 2026 - Dự đoán vô địch MLBB & Nhận quà',
            'meta_description' => 'Tham gia dự đoán đội vô địch M7 2026 tại Jakarta. Xem danh sách đội tuyển, phân tích meta hero và nhận quà hấp dẫn từ LamGame.',
            'meta_keywords'    => 'M7 2026, MLBB M7, M7 World Championship, dự đoán M7, meta MLBB M7 2026',
            'og_image'         => null,
            'status'           => true,
            'start_at'         => '2026-02-24 00:00:00',
            'end_at'           => '2026-03-25 23:59:59',
            'author'           => 'LamGame',
            'author_id'        => 1,
            'views'            => 0,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('landing_pages')->where('slug', 'meta-mlbb-m7-2026')->delete();
    }
};
