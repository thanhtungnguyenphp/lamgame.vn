<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductDownloadableLink;
use Webkul\Product\Models\ProductFlat;
use DB;
use Carbon\Carbon;

class MiniGameProductsSyncSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // All 48 games from kho_game_free/output
        $games = [
            // === 14 games that MAP to existing products ===
            ['folder' => 'ran-san-moi', 'name' => 'Rắn Săn Mồi', 'name_en' => 'Snake Classic', 'sku' => 'snake-classic', 'product_id' => 56, 'desc' => 'Game rắn săn mồi kinh điển HTML5, điều khiển rắn ăn mồi và tránh va chạm.'],
            ['folder' => 'chim-bay-vuot-ong', 'name' => 'Chim Bay Vượt Ống', 'name_en' => 'Flappy Bird Clone', 'sku' => 'flappy-bird-clone', 'product_id' => 57, 'desc' => 'Game Flappy Bird HTML5, điều khiển chim bay qua các ống cản.'],
            ['folder' => 'xep-gach-kinh-dien', 'name' => 'Xếp Gạch Kinh Điển', 'name_en' => 'Tetris HTML5', 'sku' => 'tetris-html5', 'product_id' => 58, 'desc' => 'Game Tetris kinh điển HTML5 với rotation, line clearing và tăng tốc.'],
            ['folder' => 'bong-ban-mini', 'name' => 'Bóng Bàn Mini', 'name_en' => 'Pong Multiplayer', 'sku' => 'pong-multiplayer', 'product_id' => 59, 'desc' => 'Game Pong 2 người chơi HTML5, bóng bàn mini kinh điển.'],
            ['folder' => 'pha-gach', 'name' => 'Phá Gạch', 'name_en' => 'Breakout Game', 'sku' => 'breakout-game', 'product_id' => 60, 'desc' => 'Game phá gạch Breakout HTML5 với nhiều level và power-ups.'],
            ['folder' => 'phong-thu', 'name' => 'Phòng Thủ', 'name_en' => 'Tower Defense', 'sku' => 'tower-defense', 'product_id' => 61, 'desc' => 'Game phòng thủ tháp HTML5, đặt tháp chống lại quái vật.'],
            ['folder' => 'keo-ngot-xep-3', 'name' => 'Kẹo Ngọt Xếp 3', 'name_en' => 'Match-3 Candy', 'sku' => 'match3-candy', 'product_id' => 62, 'desc' => 'Game xếp 3 kẹo ngọt HTML5 kiểu Candy Crush.'],
            ['folder' => 'chay-bat-tan', 'name' => 'Chạy Bất Tận', 'name_en' => 'Endless Runner', 'sku' => 'endless-runner', 'product_id' => 63, 'desc' => 'Game chạy bất tận HTML5, né chướng ngại vật và thu thập điểm.'],
            ['folder' => 'xep-bai-mot-minh', 'name' => 'Xếp Bài Một Mình', 'name_en' => 'Solitaire', 'sku' => 'card-game-engine', 'product_id' => 64, 'desc' => 'Game xếp bài Solitaire kinh điển HTML5.'],
            ['folder' => 'ban-tau', 'name' => 'Bắn Tàu', 'name_en' => 'Top-Down Shooter', 'sku' => 'top-down-shooter', 'product_id' => 65, 'desc' => 'Game bắn tàu top-down HTML5 với nhiều loại đạn và enemy.'],
            ['folder' => 'do-vui-kien-thuc', 'name' => 'Đố Vui Kiến Thức', 'name_en' => 'Quiz Trivia', 'sku' => 'quiz-trivia', 'product_id' => 68, 'desc' => 'Game đố vui kiến thức HTML5 với nhiều chủ đề.'],
            ['folder' => 'ban-bong-bong', 'name' => 'Bắn Bóng Bóng', 'name_en' => 'Bubble Shooter', 'sku' => 'bubble-shooter', 'product_id' => 69, 'desc' => 'Game bắn bóng bóng HTML5, ghép 3 bóng cùng màu.'],
            ['folder' => 'co-vua-online', 'name' => 'Cờ Vua Online', 'name_en' => 'Chess with AI', 'sku' => 'chess-ai', 'product_id' => 70, 'desc' => 'Game cờ vua HTML5 với AI đối thủ.'],
            ['folder' => 'thoat-me-cung', 'name' => 'Thoát Mê Cung', 'name_en' => 'Maze Runner', 'sku' => 'roguelike-dungeon', 'product_id' => 75, 'desc' => 'Game thoát mê cung HTML5, tìm đường ra khỏi mê cung.'],

            // === 34 NEW games ===
            ['folder' => '2048-ghep-so', 'name' => '2048 Ghép Số', 'name_en' => '2048 Number Puzzle', 'sku' => '2048-ghep-so', 'desc' => 'Game 2048 ghép số kinh điển HTML5, trượt ô số để ghép thành 2048.'],
            ['folder' => 'ban-cung', 'name' => 'Bắn Cung', 'name_en' => 'Archery Game', 'sku' => 'ban-cung', 'desc' => 'Game bắn cung HTML5, nhắm và bắn trúng mục tiêu.'],
            ['folder' => 'ban-quai-vu-tru', 'name' => 'Bắn Quái Vũ Trụ', 'name_en' => 'Space Invaders', 'sku' => 'ban-quai-vu-tru', 'desc' => 'Game bắn quái vũ trụ HTML5 kiểu Space Invaders kinh điển.'],
            ['folder' => 'bat-emoji', 'name' => 'Bắt Emoji', 'name_en' => 'Catch Emoji', 'sku' => 'bat-emoji', 'desc' => 'Game bắt emoji rơi HTML5, thu thập emoji đúng để ghi điểm.'],
            ['folder' => 'bong-nay', 'name' => 'Bóng Nảy', 'name_en' => 'Bounce Ball', 'sku' => 'bong-nay', 'desc' => 'Game bóng nảy HTML5, điều khiển bóng vượt chướng ngại vật.'],
            ['folder' => 'chem-hoa-qua', 'name' => 'Chém Hoa Quả', 'name_en' => 'Fruit Ninja', 'sku' => 'chem-hoa-qua', 'desc' => 'Game chém hoa quả HTML5 kiểu Fruit Ninja, vuốt để chém trái cây.'],
            ['folder' => 'click-banh', 'name' => 'Click Bánh', 'name_en' => 'Cookie Clicker', 'sku' => 'click-banh', 'desc' => 'Game click bánh HTML5 kiểu Cookie Clicker, nhấp để sản xuất bánh.'],
            ['folder' => 'click-hinh', 'name' => 'Click Hình', 'name_en' => 'Shape Clicker', 'sku' => 'click-hinh', 'desc' => 'Game click hình HTML5, nhấp đúng hình để ghi điểm.'],
            ['folder' => 'co-ca-ro', 'name' => 'Cờ Ca Rô', 'name_en' => 'Tic Tac Toe', 'sku' => 'co-ca-ro', 'desc' => 'Game cờ ca rô HTML5, chơi 2 người hoặc với máy.'],
            ['folder' => 'co-dam', 'name' => 'Cờ Đam', 'name_en' => 'Checkers', 'sku' => 'co-dam', 'desc' => 'Game cờ đam HTML5, chơi 2 người hoặc với AI.'],
            ['folder' => 'dap-chuot', 'name' => 'Đập Chuột', 'name_en' => 'Whack a Mole', 'sku' => 'dap-chuot', 'desc' => 'Game đập chuột HTML5 kiểu Whack-a-Mole kinh điển.'],
            ['folder' => 'do-min', 'name' => 'Dò Mìn', 'name_en' => 'Minesweeper', 'sku' => 'do-min', 'desc' => 'Game dò mìn Minesweeper HTML5 kinh điển.'],
            ['folder' => 'doan-chu', 'name' => 'Đoán Chữ', 'name_en' => 'Hangman', 'sku' => 'doan-chu', 'desc' => 'Game đoán chữ Hangman HTML5, đoán từ trước khi hết lượt.'],
            ['folder' => 'doan-tu', 'name' => 'Đoán Từ', 'name_en' => 'Wordle Clone', 'sku' => 'doan-tu', 'desc' => 'Game đoán từ HTML5 kiểu Wordle, đoán từ 5 chữ cái.'],
            ['folder' => 'ech-qua-duong', 'name' => 'Ếch Qua Đường', 'name_en' => 'Frogger', 'sku' => 'ech-qua-duong', 'desc' => 'Game ếch qua đường HTML5 kiểu Frogger kinh điển.'],
            ['folder' => 'go-phim-nhanh', 'name' => 'Gõ Phím Nhanh', 'name_en' => 'Typing Speed', 'sku' => 'go-phim-nhanh', 'desc' => 'Game luyện gõ phím nhanh HTML5, cải thiện tốc độ đánh máy.'],
            ['folder' => 'hung-con-trung', 'name' => 'Hứng Côn Trùng', 'name_en' => 'Bug Catcher', 'sku' => 'hung-con-trung', 'desc' => 'Game hứng côn trùng HTML5, bắt côn trùng rơi xuống.'],
            ['folder' => 'keo-bua-bao', 'name' => 'Kéo Búa Bao', 'name_en' => 'Rock Paper Scissors', 'sku' => 'keo-bua-bao', 'desc' => 'Game kéo búa bao HTML5, chơi với máy tính.'],
            ['folder' => 'kim-cuong', 'name' => 'Kim Cương', 'name_en' => 'Diamond Match', 'sku' => 'kim-cuong', 'desc' => 'Game xếp kim cương HTML5 kiểu Bejeweled.'],
            ['folder' => 'lat-the-nho', 'name' => 'Lật Thẻ Nhớ', 'name_en' => 'Memory Card', 'sku' => 'lat-the-nho', 'desc' => 'Game lật thẻ nhớ HTML5, tìm cặp thẻ giống nhau.'],
            ['folder' => 'ma-tran-an-diem', 'name' => 'Ma Trận Ăn Điểm', 'name_en' => 'Pac-Man Clone', 'sku' => 'ma-tran-an-diem', 'desc' => 'Game Pac-Man HTML5, ăn điểm và tránh ma trong mê cung.'],
            ['folder' => 'may-xeng-may-man', 'name' => 'Máy Xèng May Mắn', 'name_en' => 'Slot Machine', 'sku' => 'may-xeng-may-man', 'desc' => 'Game máy xèng may mắn HTML5, quay và thử vận may.'],
            ['folder' => 'nguoi-que', 'name' => 'Người Que', 'name_en' => 'Stickman Game', 'sku' => 'nguoi-que', 'desc' => 'Game người que HTML5, điều khiển nhân vật que chiến đấu.'],
            ['folder' => 'nhay-hinh-hoc', 'name' => 'Nhảy Hình Học', 'name_en' => 'Geometry Dash', 'sku' => 'nhay-hinh-hoc', 'desc' => 'Game nhảy hình học HTML5 kiểu Geometry Dash.'],
            ['folder' => 'nhen-xep-bai', 'name' => 'Nhện Xếp Bài', 'name_en' => 'Spider Solitaire', 'sku' => 'nhen-xep-bai', 'desc' => 'Game nhện xếp bài Spider Solitaire HTML5.'],
            ['folder' => 'noi-4', 'name' => 'Nối 4', 'name_en' => 'Connect Four', 'sku' => 'noi-4', 'desc' => 'Game nối 4 HTML5, thả quân để nối 4 quân liên tiếp.'],
            ['folder' => 'qua-duong-an-toan', 'name' => 'Qua Đường An Toàn', 'name_en' => 'Crossy Road', 'sku' => 'qua-duong-an-toan', 'desc' => 'Game qua đường an toàn HTML5 kiểu Crossy Road.'],
            ['folder' => 'ran-io', 'name' => 'Rắn IO', 'name_en' => 'Snake IO', 'sku' => 'ran-io', 'desc' => 'Game rắn IO HTML5 kiểu Slither.io, ăn mồi và lớn lên.'],
            ['folder' => 'simon-noi', 'name' => 'Simon Nói', 'name_en' => 'Simon Says', 'sku' => 'simon-noi', 'desc' => 'Game Simon Says HTML5, ghi nhớ và lặp lại chuỗi màu sắc.'],
            ['folder' => 'sudoku-vui', 'name' => 'Sudoku Vui', 'name_en' => 'Sudoku', 'sku' => 'sudoku-vui', 'desc' => 'Game Sudoku HTML5 với nhiều mức độ khó.'],
            ['folder' => 'test-phan-xa', 'name' => 'Test Phản Xạ', 'name_en' => 'Reaction Test', 'sku' => 'test-phan-xa', 'desc' => 'Game test phản xạ HTML5, đo tốc độ phản ứng.'],
            ['folder' => 'xep-hinh-truot', 'name' => 'Xếp Hình Trượt', 'name_en' => 'Sliding Puzzle', 'sku' => 'xep-hinh-truot', 'desc' => 'Game xếp hình trượt HTML5, trượt ô để hoàn thành hình.'],
            ['folder' => 'xep-thap', 'name' => 'Xếp Tháp', 'name_en' => 'Stack Tower', 'sku' => 'xep-thap', 'desc' => 'Game xếp tháp HTML5, xếp các khối chồng lên nhau.'],
            ['folder' => 'xuc-xac-may-man', 'name' => 'Xúc Xắc May Mắn', 'name_en' => 'Lucky Dice', 'sku' => 'xuc-xac-may-man', 'desc' => 'Game xúc xắc may mắn HTML5, tung xúc xắc và thử vận may.'],
        ];

        $engineId = 35;  // HTML5/JavaScript
        $langValue = '41'; // JavaScript (option_id as text for select)
        $categoryId = 2; // Source Code Game

        $created = 0;
        $updated = 0;

        foreach ($games as $game) {
            $isUpdate = isset($game['product_id']);
            $productId = $game['product_id'] ?? null;

            if ($isUpdate) {
                // UPDATE existing: update download link file path
                DB::table('product_downloadable_links')
                    ->where('product_id', $productId)
                    ->update([
                        'file' => "product_downloadable_links/{$game['folder']}/{$game['folder']}.zip",
                        'file_name' => "{$game['folder']}.zip",
                        'updated_at' => $now,
                    ]);

                // Update name in product_flat
                DB::table('product_flat')
                    ->where('product_id', $productId)
                    ->where('locale', 'vi')
                    ->update(['name' => $game['name'], 'short_description' => $game['desc'], 'description' => $game['desc']]);

                // Update attribute_values name vi
                DB::table('product_attribute_values')
                    ->where('product_id', $productId)
                    ->where('attribute_id', 2)
                    ->where('locale', 'vi')
                    ->update(['text_value' => $game['name']]);

                // Update descriptions vi
                DB::table('product_attribute_values')
                    ->where('product_id', $productId)
                    ->where('attribute_id', 9)
                    ->where('locale', 'vi')
                    ->update(['text_value' => $game['desc']]);
                DB::table('product_attribute_values')
                    ->where('product_id', $productId)
                    ->where('attribute_id', 10)
                    ->where('locale', 'vi')
                    ->update(['text_value' => $game['desc']]);

                $updated++;
                $this->command->info("Updated: {$game['name']} (ID {$productId})");
            } else {
                // CREATE new product
                if (Product::where('sku', $game['sku'])->exists()) {
                    $this->command->warn("SKU {$game['sku']} exists, skipping.");
                    continue;
                }

                $productId = DB::table('products')->insertGetId([
                    'sku' => $game['sku'],
                    'type' => 'downloadable',
                    'attribute_family_id' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // Category
                DB::table('product_categories')->insert([
                    'product_id' => $productId,
                    'category_id' => $categoryId,
                ]);

                // Channel
                DB::table('product_channels')->insert([
                    'product_id' => $productId,
                    'channel_id' => 1,
                ]);

                // Attribute values (non-locale)
                $attrs = [
                    ['attribute_id' => 1, 'text_value' => $game['sku'], 'locale' => null, 'channel' => null],
                    ['attribute_id' => 7, 'boolean_value' => 1, 'locale' => null, 'channel' => null], // visible
                    ['attribute_id' => 11, 'float_value' => 0.0000, 'locale' => null, 'channel' => null], // price (free)
                    ['attribute_id' => 8, 'boolean_value' => 1, 'locale' => null, 'channel' => 'default'], // status
                    ['attribute_id' => 31, 'integer_value' => $engineId, 'locale' => null, 'channel' => null], // game_engine
                    ['attribute_id' => 32, 'text_value' => $langValue, 'locale' => null, 'channel' => null], // programming_language
                    ['attribute_id' => 57, 'text_value' => '2 MB', 'locale' => null, 'channel' => null], // file_size
                    ['attribute_id' => 58, 'text_value' => (string) rand(100, 999), 'locale' => null, 'channel' => null], // downloads_count
                    ['attribute_id' => 59, 'text_value' => number_format(rand(40, 49) / 10, 1), 'locale' => null, 'channel' => null], // rating
                ];

                // Locale-specific (vi + en)
                foreach (['vi', 'en'] as $locale) {
                    $localeName = $locale === 'vi' ? $game['name'] : $game['name_en'];
                    $attrs[] = ['attribute_id' => 2, 'text_value' => $localeName, 'locale' => $locale, 'channel' => null]; // name
                    $attrs[] = ['attribute_id' => 3, 'text_value' => $game['sku'], 'locale' => $locale, 'channel' => null]; // url_key
                    $attrs[] = ['attribute_id' => 9, 'text_value' => $game['desc'], 'locale' => $locale, 'channel' => null]; // short_desc
                    $attrs[] = ['attribute_id' => 10, 'text_value' => $game['desc'], 'locale' => $locale, 'channel' => null]; // desc
                }

                foreach ($attrs as $attr) {
                    DB::table('product_attribute_values')->insert([
                        'product_id' => $productId,
                        'attribute_id' => $attr['attribute_id'],
                        'locale' => $attr['locale'] ?? null,
                        'channel' => $attr['channel'] ?? null,
                        'text_value' => $attr['text_value'] ?? null,
                        'boolean_value' => $attr['boolean_value'] ?? null,
                        'integer_value' => $attr['integer_value'] ?? null,
                        'float_value' => $attr['float_value'] ?? null,
                    ]);
                }

                // Downloadable link
                DB::table('product_downloadable_links')->insert([
                    'product_id' => $productId,
                    'file' => "product_downloadable_links/{$game['folder']}/{$game['folder']}.zip",
                    'file_name' => "{$game['folder']}.zip",
                    'type' => 'file',
                    'price' => 0,
                    'downloads' => 0,
                    'sort_order' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // Downloadable link translation
                $linkId = DB::getPdo()->lastInsertId();
                DB::table('product_downloadable_link_translations')->insert([
                    ['product_downloadable_link_id' => $linkId, 'locale' => 'vi', 'title' => $game['name']],
                    ['product_downloadable_link_id' => $linkId, 'locale' => 'en', 'title' => $game['name_en']],
                ]);

                // Product flat (vi + en)
                foreach (['vi', 'en'] as $locale) {
                    $localeName = $locale === 'vi' ? $game['name'] : $game['name_en'];
                    DB::table('product_flat')->insert([
                        'sku' => $game['sku'],
                        'type' => 'downloadable',
                        'name' => $localeName,
                        'short_description' => $game['desc'],
                        'description' => $game['desc'],
                        'url_key' => $game['sku'],
                        'price' => 0,
                        'status' => 1,
                        'new' => 1,
                        'featured' => 1,
                        'visible_individually' => 1,
                        'locale' => $locale,
                        'channel' => 'default',
                        'product_id' => $productId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }

                $created++;
                $this->command->info("Created: {$game['name']} (ID {$productId})");
            }
        }

        $this->command->info("Done! Created: {$created}, Updated: {$updated}");
    }
}
