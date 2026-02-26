<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Landing page
        if (!DB::table('landing_pages')->where('slug', 'mini-game-m7')->exists()) {
            DB::table('landing_pages')->insert([
                'name'             => 'Pick\'em M7 2026 - Dự đoán vô địch & Nhận quà',
                'slug'             => 'mini-game-m7',
                'template'         => 'mini-game',
                'hero_title'       => '🏆 PICK\'EM M7 2026',
                'hero_subtitle'    => 'Dự đoán đội vô địch M7 World Championship 2026. Đúng = Nhận quà cực hot từ LamGame!',
                'hero_cta_text'    => null,
                'hero_cta_url'     => null,
                'hero_bg_image'    => null,
                'hero_bg_color'    => '#0f0c29',
                'description'      => '<h2>Thể lệ Mini Game</h2><ul><li>🏆 Dự đoán đội vô địch: <strong>100 điểm</strong> nếu đúng</li><li>⚔️ Dự đoán từng trận: <strong>10 điểm</strong> mỗi trận đúng</li><li>🎁 Top 1-3 bảng xếp hạng nhận quà từ LamGame</li><li>⏰ Dự đoán trận đấu phải gửi trước giờ thi đấu</li><li>🔄 Có thể đổi dự đoán trước khi trận bắt đầu</li></ul><p><strong>Lưu ý:</strong> Đăng nhập tài khoản LamGame để tích điểm và lên bảng xếp hạng. Khách vẫn chơi được nhưng không tính điểm.</p>',
                'sections'         => json_encode([
                    'prizes' => [
                        'items' => [
                            ['rank' => '🥇 Top 1', 'value' => '500K', 'desc' => 'Voucher LamGame'],
                            ['rank' => '🥈 Top 2', 'value' => '300K', 'desc' => 'Voucher LamGame'],
                            ['rank' => '🥉 Top 3', 'value' => '200K', 'desc' => 'Voucher LamGame'],
                        ],
                    ],
                    'teams' => [
                        'items' => [
                            ['flag' => '🇵🇭', 'name' => 'Aurora Gaming PH', 'region' => 'Philippines'],
                            ['flag' => '🇵🇭', 'name' => 'Team Liquid PH', 'region' => 'Philippines'],
                            ['flag' => '🇮🇩', 'name' => 'ONIC Esports', 'region' => 'Indonesia'],
                            ['flag' => '🇮🇩', 'name' => 'Alter Ego', 'region' => 'Indonesia'],
                            ['flag' => '🇲🇾', 'name' => 'Selangor Red Giants', 'region' => 'Malaysia'],
                            ['flag' => '🇲🇾', 'name' => 'CG Esports', 'region' => 'Malaysia'],
                            ['flag' => '🇻🇳', 'name' => 'RLG SE', 'region' => 'Việt Nam'],
                            ['flag' => '🌏', 'name' => 'Team Spirit', 'region' => 'EECA'],
                            ['flag' => '🌏', 'name' => 'Team Falcons', 'region' => 'MENA'],
                            ['flag' => '🇨🇳', 'name' => 'DianFengYaoGuai', 'region' => 'Trung Quốc'],
                        ],
                    ],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'meta_title'       => 'Pick\'em M7 2026 - Dự đoán vô địch MLBB & Nhận quà | LamGame',
                'meta_description' => 'Tham gia mini game dự đoán đội vô địch M7 World Championship 2026. Dự đoán đúng nhận voucher từ LamGame!',
                'meta_keywords'    => 'M7 2026 prediction, dự đoán M7, pick em M7, MLBB M7 mini game',
                'og_image'         => null,
                'status'           => true,
                'start_at'         => '2026-02-25 00:00:00',
                'end_at'           => '2026-04-05 23:59:59',
                'author'           => 'LamGame',
                'author_id'        => 1,
                'views'            => 0,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }

        // Sample matches — Swiss Stage Round 1 (03/03)
        if (DB::table('m7_matches')->count() === 0) {
            $matches = [
                ['round' => 'Swiss Round 1', 'team_a' => 'Aurora Gaming PH', 'team_b' => 'Alter Ego', 'match_at' => '2026-03-03 13:00:00'],
                ['round' => 'Swiss Round 1', 'team_a' => 'ONIC Esports', 'team_b' => 'Team Liquid PH', 'match_at' => '2026-03-03 15:00:00'],
                ['round' => 'Swiss Round 1', 'team_a' => 'Selangor Red Giants', 'team_b' => 'Team Spirit', 'match_at' => '2026-03-03 17:00:00'],
                ['round' => 'Swiss Round 1', 'team_a' => 'RLG SE', 'team_b' => 'DianFengYaoGuai', 'match_at' => '2026-03-03 19:00:00'],
                ['round' => 'Swiss Round 1', 'team_a' => 'CG Esports', 'team_b' => 'Team Falcons', 'match_at' => '2026-03-03 21:00:00'],
            ];
            foreach ($matches as $m) {
                DB::table('m7_matches')->insert(array_merge($m, [
                    'winner' => null, 'status' => 0,
                    'created_at' => now(), 'updated_at' => now(),
                ]));
            }
        }
    }

    public function down(): void
    {
        DB::table('m7_matches')->truncate();
        DB::table('landing_pages')->where('slug', 'mini-game-m7')->delete();
    }
};
