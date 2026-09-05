<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RuntimeException;

class RemediateBlogP0 extends Command
{
    protected $signature = 'blog:remediate-p0 {--apply : Apply the reviewed editorial changes in one transaction}';

    protected $description = 'Remediate the three P0 blog articles and archive the duplicate Dirty Bomb URL';

    private const EXPECTED_SLUGS = [
        7 => 'top-10-game-pc-cu-nhung-van-cuc-hay-2024',
        8 => 'grand-chase-m-game-nhap-vai-hanh-dong-anime-3d-sieu-hot',
        20 => 'dirty-bomb-game-fps-mien-phi-hay-nhat-tu-splash-damage',
        21 => 'dirty-bomb-danh-gia-chi-tiet-game-fps-mien-phi',
        76 => 'tools-phat-trien-game-html5-tot-nhat-2024',
    ];

    public function handle(): int
    {
        $articles = $this->articles();
        $this->validatePayload($articles);

        $records = DB::table('blogs')
            ->whereIn('id', array_keys(self::EXPECTED_SLUGS))
            ->orderBy('id')
            ->get();

        $this->validateRecords($records);

        foreach ($articles as $id => $article) {
            $this->line(sprintf(
                '[%d] %s — %d từ, meta %d ký tự, %d nguồn',
                $id,
                $article['name'],
                $this->wordCount($article['description']),
                mb_strlen($article['meta_description']),
                count(json_decode($article['sources'], true, flags: JSON_THROW_ON_ERROR))
            ));
        }
        $this->line('[20] archive duplicate; [21] giữ published làm URL canonical.');

        if (! $this->option('apply')) {
            $this->warn('Dry run: không có dữ liệu nào được thay đổi. Dùng --apply để thực thi.');

            return self::SUCCESS;
        }

        $backupPath = $this->writeBackup($records);

        DB::transaction(function () use ($articles): void {
            $lockedRecords = DB::table('blogs')
                ->whereIn('id', array_keys(self::EXPECTED_SLUGS))
                ->lockForUpdate()
                ->get();

            $this->validateRecords($lockedRecords);

            if (! DB::table('authors')->where('id', 1)->exists()) {
                throw new RuntimeException('Author ID 1 (LamGame Team) không tồn tại.');
            }

            $reviewedAt = now();

            foreach ($articles as $id => $article) {
                $affected = DB::table('blogs')
                    ->where('id', $id)
                    ->where('slug', self::EXPECTED_SLUGS[$id])
                    ->update($article + [
                        'status' => 'published',
                        'reviewed_at' => $reviewedAt,
                        'reviewed_by' => 1,
                        'updated_at' => $reviewedAt,
                    ]);

                if ($affected !== 1) {
                    throw new RuntimeException("Không thể cập nhật blog ID {$id}.");
                }
            }

            $archived = DB::table('blogs')
                ->where('id', 20)
                ->where('slug', self::EXPECTED_SLUGS[20])
                ->update([
                    'status' => 'archived',
                    'updated_at' => $reviewedAt,
                ]);

            if ($archived !== 1) {
                throw new RuntimeException('Không thể archive blog Dirty Bomb ID 20.');
            }

            $canonicalStatus = DB::table('blogs')->where('id', 21)->value('status');
            if ($canonicalStatus !== 'published') {
                throw new RuntimeException('Blog Dirty Bomb canonical ID 21 không còn published.');
            }
        }, 3);

        $this->info('Đã hoàn tất transaction P0 blog.');
        $this->line("Backup: {$backupPath}");
        $this->line('SHA-256: '.hash_file('sha256', $backupPath));

        return self::SUCCESS;
    }

    private function validatePayload(array $articles): void
    {
        foreach ($articles as $id => $article) {
            if ($this->wordCount($article['description']) < 900) {
                throw new RuntimeException("Bài ID {$id} dưới 900 từ sau biên tập.");
            }

            if (mb_strlen($article['meta_description']) > 160 || mb_strlen($article['short_description']) > 160) {
                throw new RuntimeException("Bài ID {$id} có description vượt 160 ký tự.");
            }

            $sources = json_decode($article['sources'], true, flags: JSON_THROW_ON_ERROR);
            if (! is_array($sources) || count($sources) < 2) {
                throw new RuntimeException("Bài ID {$id} thiếu nguồn đã kiểm tra.");
            }
        }
    }

    private function validateRecords($records): void
    {
        if ($records->count() !== count(self::EXPECTED_SLUGS)) {
            throw new RuntimeException('Không tìm thấy đủ 5 record blog P0.');
        }

        foreach (self::EXPECTED_SLUGS as $id => $slug) {
            $record = $records->firstWhere('id', $id);
            if (! $record || $record->slug !== $slug) {
                throw new RuntimeException("Record ID {$id} không khớp slug dự kiến.");
            }
        }
    }

    private function writeBackup($records): string
    {
        $directory = storage_path('app/private/backups');
        File::ensureDirectoryExists($directory, 0700, true);
        $path = $directory.'/blog-p0-remediation-'.now()->format('Y-m-d-His').'.json';
        $payload = json_encode([
            'created_at' => now()->toIso8601String(),
            'reason' => 'Pre-transaction backup for blog P0 remediation',
            'record_ids' => array_keys(self::EXPECTED_SLUGS),
            'records' => $records->values()->all(),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        if (File::put($path, $payload) === false) {
            throw new RuntimeException('Không thể ghi backup private.');
        }
        chmod($path, 0600);

        return $path;
    }

    private function wordCount(string $html): int
    {
        preg_match_all('/[\p{L}\p{N}]+/u', strip_tags($html), $matches);

        return count($matches[0] ?? []);
    }

    private function encodeSources(array $sources): string
    {
        return json_encode($sources, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }

    private function articles(): array
    {
        return [
            7 => [
                'name' => '10 game PC cũ vẫn đáng chơi và nghiên cứu thiết kế',
                'short_description' => 'Danh sách 10 game PC lâu năm còn giá trị, kèm tiêu chí chọn, lưu ý tương thích và bài học thiết kế dành cho game developer.',
                'description' => <<<'HTML'
<p>“Game PC cũ” trong bài này không đồng nghĩa với một bảng xếp hạng tuyệt đối. Chúng tôi chọn các trò chơi đã phát hành nhiều năm, vẫn có trang phân phối hoặc nguồn chính thức để người đọc kiểm tra, và quan trọng hơn: mỗi game đại diện cho một bài học thiết kế có thể áp dụng vào dự án hiện nay. Một số bản đang bán là bản cập nhật hoặc remaster nên cấu hình thực tế có thể cao hơn bản gốc.</p>

<h2>Tiêu chí chọn game</h2>
<p>Danh sách ưu tiên bốn yếu tố: vòng lặp chơi cốt lõi rõ ràng; hệ thống có thể phân tích mà không cần dựa vào hoài niệm; khả năng tiếp cận hợp pháp trên PC; và cộng đồng hoặc tài liệu đủ để người mới tự xử lý lỗi. Chúng tôi không dùng số người chơi đồng thời để tuyên bố game nào “hay nhất”, bởi chỉ số đó thay đổi theo thời điểm và không phản ánh đầy đủ giá trị thiết kế.</p>
<p>Trước khi mua, hãy đọc cấu hình trên trang chính thức, kiểm tra hệ điều hành, driver đồ họa, ngôn ngữ và khu vực phát hành. Với laptop cũ, nên ưu tiên độ phân giải thấp hơn, giới hạn khung hình và tắt hiệu ứng hậu kỳ trước khi giảm chất lượng texture. Mod có thể kéo dài tuổi thọ trò chơi nhưng cũng có thể gây lỗi save; hãy sao lưu dữ liệu và chỉ tải từ nguồn cộng đồng đáng tin cậy.</p>

<h2>1. Half-Life 2: nhịp dẫn dắt không cần bảng hướng dẫn dày đặc</h2>
<p>Half-Life 2 là ví dụ dễ quan sát về cách dùng bố cục, ánh sáng, chuyển động của NPC và điểm tương phản để dẫn người chơi. Nhiều khu vực không cần mũi tên lớn nhưng người chơi vẫn đoán được đường đi vì môi trường liên tục cung cấp tín hiệu. Nhịp độ cũng thay đổi giữa khám phá, giải đố vật lý và chiến đấu để tránh một hành động bị lặp quá lâu.</p>
<p>Bài học cho developer là “dạy bằng tình huống”: giới thiệu một cơ chế ở nơi an toàn, cho người chơi thử, sau đó kết hợp nó với áp lực. Khi làm prototype, hãy tắt toàn bộ marker và quan sát tester; nơi họ dừng lại chính là dữ liệu để chỉnh level, không nhất thiết là lý do để thêm thêm chữ.</p>

<h2>2. Portal 2: mở rộng một cơ chế bằng tổ hợp</h2>
<p>Portal 2 bắt đầu từ quy tắc đơn giản về hai cổng liên kết rồi mở rộng bằng động lượng, bề mặt, laser, gel và vật thể tương tác. Độ sâu đến từ việc kết hợp các thành phần quen thuộc thay vì liên tục thêm nút bấm mới. Phần trình bày và thoại lấp khoảng trống giữa các puzzle, giúp nhịp chơi không trở thành chuỗi phòng thử nghiệm khô cứng.</p>
<p>Khi thiết kế puzzle, hãy lập ma trận giữa cơ chế chính và từng biến thể môi trường. Mỗi màn nên kiểm tra một ý tưởng rõ ràng; nếu lời giải yêu cầu thao tác chính xác ngoài chủ đích, đó có thể là vấn đề về điều khiển chứ không phải độ khó tốt.</p>

<h2>3. Left 4 Dead 2: điều phối cường độ co-op</h2>
<p>Left 4 Dead 2 cho thấy một màn tuyến tính vẫn có khả năng tạo trải nghiệm khác nhau nhờ thay đổi áp lực, vật phẩm và thời điểm xuất hiện của kẻ địch. Co-op hoạt động tốt khi vai trò nảy sinh từ tình huống: người chơi phải quan sát, cứu nhau và chia sẻ tài nguyên, thay vì chỉ đứng cạnh nhau để tăng sát thương.</p>
<p>Developer làm game đội nhóm có thể học cách tạo trạng thái “căng – nghỉ – chuẩn bị – bùng nổ”. Nếu cường độ luôn ở mức tối đa, người chơi khó nhận ra khoảnh khắc đặc biệt. Telemetry nên ghi thời gian đội hình bị tách, lượng tài nguyên còn lại và điểm thất bại để cân bằng theo nhóm, không chỉ theo một người chơi giỏi.</p>

<h2>4. Age of Empires II: Definitive Edition — kinh tế tạo ra quyết định chiến thuật</h2>
<p>Cốt lõi Age of Empires II là phân bổ tài nguyên dưới áp lực thông tin không đầy đủ. Một đơn vị mạnh không tồn tại độc lập: nó cần thời gian, công trình, công nghệ và chuỗi kinh tế phù hợp. Bản Definitive Edition là cách tiếp cận thuận tiện trên hệ thống hiện đại, nhưng người dùng máy yếu phải kiểm tra cấu hình vì đây không phải bản phát hành năm 1999.</p>
<p>Bài học đáng chú ý là tạo “chi phí cơ hội”. Khi mọi nâng cấp đều mua được ngay, quyết định mất ý nghĩa. Một hệ thống chiến thuật tốt buộc người chơi đánh đổi giữa sức mạnh hiện tại, khả năng mở rộng và thông tin về đối thủ.</p>

<h2>5. StarCraft II: khả năng đọc trận đấu và phản hồi nhanh</h2>
<p>StarCraft II phân biệt rõ silhouette, hiệu ứng và vai trò đơn vị, giúp người chơi đọc giao tranh trong thời gian ngắn. Ba chủng tộc có cách sản xuất và mở rộng khác nhau nhưng cùng vận hành trên nền kinh tế và mục tiêu bản đồ có thể so sánh. Đây là tài liệu tham khảo hữu ích về thiết kế bất đối xứng.</p>
<p>Với game cạnh tranh, developer cần ưu tiên tính dễ đọc trước vẻ đẹp của từng hiệu ứng riêng lẻ. Màu đội, âm thanh cảnh báo và hình dáng phải còn phân biệt được khi nhiều đối tượng chồng lên nhau. Cân bằng cũng nên dựa trên từng nhóm kỹ năng và bản đồ, không chỉ một tỷ lệ thắng tổng.</p>

<h2>6. Sid Meier’s Civilization V: biến “thêm một lượt” thành chuỗi mục tiêu</h2>
<p>Civilization V liên tục đặt mục tiêu ở nhiều thang thời gian: hoàn tất một công nghệ trong vài lượt, xây kỳ quan trong trung hạn, và theo đuổi điều kiện chiến thắng dài hạn. Người chơi thường muốn thực hiện thêm một lượt vì luôn có một kết quả sắp xảy ra. Bản đồ lục giác và giới hạn một đơn vị chiến đấu trên một ô cũng làm vị trí trở nên dễ đọc.</p>
<p>Điều cần học không phải là nhồi nhiều thanh tiến trình. Mỗi tiến trình nên mở một quyết định mới hoặc thay đổi kế hoạch. Nếu phần thưởng chỉ tăng số nhỏ mà không ảnh hưởng cách chơi, cảm giác tiến bộ sẽ nhanh chóng suy yếu.</p>

<h2>7. Fallout: New Vegas — lựa chọn được phản ánh trong hệ thống</h2>
<p>Fallout: New Vegas thường được nhắc tới vì nhiệm vụ có nhiều hướng giải quyết, nhưng giá trị thiết kế nằm ở quan hệ giữa kỹ năng, phe phái, hội thoại và hậu quả. Lựa chọn có sức nặng khi game ghi nhận nó ở các lớp khác nhau, thay vì chỉ đổi một đoạn kết.</p>
<p>Với RPG, hãy thiết kế nhiệm vụ bằng sơ đồ trạng thái: điều kiện đầu vào, nhân vật biết thông tin gì, phe nào thay đổi thái độ và thế giới phản hồi ra sao. Cần kiểm thử đường đi ngoài dự kiến vì hệ thống tự do dễ tạo trạng thái nhiệm vụ bị kẹt. Bản PC cũ cũng có thể cần bản vá cộng đồng; người chơi nên đọc hướng dẫn hiện hành và sao lưu save.</p>

<h2>8. Terraria: chiều sâu từ tương tác giữa các hệ thống</h2>
<p>Terraria kết nối khám phá, thu thập, chế tạo, xây dựng và chiến đấu trong một vòng lặp mà phần thưởng của hoạt động này mở lựa chọn cho hoạt động khác. Progression không chỉ là chỉ số nhân vật; công cụ mới còn thay đổi cách người chơi đi qua thế giới và tiếp cận tài nguyên.</p>
<p>Khi xây sandbox, developer nên tìm các “động từ” có thể kết hợp. Một vật liệu chỉ dùng cho một công thức sẽ tạo nội dung tuyến tính; vật liệu tham gia xây dựng, chiến đấu và trang trí sẽ tạo nhiều câu chuyện cá nhân hơn. Tuy nhiên, công thức và UI phải hỗ trợ khám phá để chiều sâu không biến thành gánh nặng tra cứu.</p>

<h2>9. The Elder Scrolls V: Skyrim Special Edition — khám phá dựa trên điểm thu hút</h2>
<p>Skyrim sắp xếp đường chân trời, địa danh và sự kiện ngẫu nhiên để người chơi thường nhìn thấy một mục tiêu phụ khi đang đi tới mục tiêu chính. Cách bố trí này tạo cảm giác tự do nhưng vẫn giữ dòng hoạt động. Special Edition là bản phân phối phổ biến hiện nay và có yêu cầu khác bản gốc, vì vậy không nên mặc định mọi máy cũ đều chạy tốt.</p>
<p>Bài học level design là tạo mật độ điểm quan tâm vừa đủ và thay đổi phần thưởng. Nếu mọi biểu tượng dẫn tới cùng một cấu trúc, bản đồ lớn chỉ làm tăng thời gian di chuyển. Mod hỗ trợ khả năng chơi lại nhưng cần kiểm soát phiên bản và thứ tự tải; một bộ mod nhỏ, có tài liệu rõ thường ổn định hơn danh sách dài.</p>

<h2>10. Stardew Valley: lịch ngày biến việc nhỏ thành kế hoạch</h2>
<p>Stardew Valley phối hợp thời gian trong ngày, mùa, năng lượng, cây trồng, quan hệ và nâng cấp trang trại. Mỗi ngày ngắn tạo giới hạn mềm: người chơi tự chọn ưu tiên mà hiếm khi bị buộc phải tối ưu tuyệt đối. Đây là ví dụ tốt về cách hệ thống thân thiện vẫn có chiều sâu chiến lược.</p>
<p>Developer có thể học cách dùng lịch để làm nội dung quay vòng và tạo dự đoán. Cần cẩn thận với tâm lý bỏ lỡ: sự kiện nên trở lại hoặc có thông tin đủ rõ, nếu không người chơi thư giãn sẽ cảm thấy bị phạt. Accessibility, remap điều khiển và tùy chọn tốc độ cũng đáng được xem như phần thiết kế cốt lõi.</p>

<h2>Chọn game nào để bắt đầu?</h2>
<p>Nếu quan tâm level design, bắt đầu với Half-Life 2 hoặc Portal 2. Nếu nghiên cứu co-op và nhịp độ, chọn Left 4 Dead 2. Age of Empires II, StarCraft II và Civilization V phù hợp để phân tích kinh tế, thông tin và quyết định chiến thuật. Fallout: New Vegas và Skyrim hữu ích cho quest cùng khám phá; Terraria và Stardew Valley cho thấy cách nhiều hệ thống nhỏ hỗ trợ một vòng lặp dài hạn.</p>
<p>Đừng chỉ chơi để hoàn thành. Hãy ghi lại lúc game giới thiệu quy tắc, thời điểm bạn hiểu mục tiêu, nguyên nhân thất bại và cách UI phản hồi. Sau mỗi phiên, thử tái tạo một cơ chế thật nhỏ trong prototype. Phân tích có giá trị nhất khi biến quan sát thành giả thuyết có thể kiểm thử.</p>

<h2>Lưu ý tương thích và mua game</h2>
<ul>
<li>Đối chiếu cấu hình tối thiểu và khuyến nghị trên trang bán chính thức tại thời điểm mua.</li>
<li>Kiểm tra game là bản gốc, remaster hay Special Edition; tên gần giống không đồng nghĩa cấu hình giống nhau.</li>
<li>Sao lưu save trước khi cài mod, thay đổi file cấu hình hoặc chuyển phiên bản.</li>
<li>Với game online, kiểm tra khu vực máy chủ và trạng thái dịch vụ; không suy ra từ bài viết cũ.</li>
<li>Mua từ cửa hàng chính thức để nhận bản cập nhật và giảm rủi ro file bị chỉnh sửa.</li>
</ul>

<h2>Nguồn tham khảo chính thức</h2>
<p>Thông tin phát hành và cấu hình được đối chiếu từ các trang sản phẩm chính thức: <a href="https://store.steampowered.com/app/220/HalfLife_2/" target="_blank" rel="noopener noreferrer">Half-Life 2</a>, <a href="https://store.steampowered.com/app/620/Portal_2/" target="_blank" rel="noopener noreferrer">Portal 2</a>, <a href="https://store.steampowered.com/app/550/Left_4_Dead_2/" target="_blank" rel="noopener noreferrer">Left 4 Dead 2</a>, <a href="https://www.ageofempires.com/games/aoeiide/" target="_blank" rel="noopener noreferrer">Age of Empires II: DE</a>, <a href="https://starcraft2.blizzard.com/" target="_blank" rel="noopener noreferrer">StarCraft II</a>, <a href="https://store.steampowered.com/app/8930/Sid_Meiers_Civilization_V/" target="_blank" rel="noopener noreferrer">Civilization V</a>, <a href="https://store.steampowered.com/app/22380/Fallout_New_Vegas/" target="_blank" rel="noopener noreferrer">Fallout: New Vegas</a>, <a href="https://store.steampowered.com/app/105600/Terraria/" target="_blank" rel="noopener noreferrer">Terraria</a>, <a href="https://store.steampowered.com/app/489830/The_Elder_Scrolls_V_Skyrim_Special_Edition/" target="_blank" rel="noopener noreferrer">Skyrim Special Edition</a> và <a href="https://www.stardewvalley.net/" target="_blank" rel="noopener noreferrer">Stardew Valley</a>. Hãy xem lại các trang này vì yêu cầu hệ thống và tình trạng phân phối có thể thay đổi.</p>
HTML,
                'meta_title' => '10 game PC cũ đáng chơi và bài học thiết kế | LamGame',
                'meta_description' => '10 game PC lâu năm còn giá trị, kèm tiêu chí chọn, lưu ý tương thích và bài học thiết kế thực tế cho game developer.',
                'meta_keywords' => 'game PC cũ, game kinh điển, game design, level design, game developer',
                'sources' => $this->encodeSources([
                    ['title' => 'Half-Life 2 on Steam', 'url' => 'https://store.steampowered.com/app/220/HalfLife_2/'],
                    ['title' => 'Portal 2 on Steam', 'url' => 'https://store.steampowered.com/app/620/Portal_2/'],
                    ['title' => 'Left 4 Dead 2 on Steam', 'url' => 'https://store.steampowered.com/app/550/Left_4_Dead_2/'],
                    ['title' => 'Age of Empires II: Definitive Edition', 'url' => 'https://www.ageofempires.com/games/aoeiide/'],
                    ['title' => 'StarCraft II official site', 'url' => 'https://starcraft2.blizzard.com/'],
                    ['title' => 'Civilization V on Steam', 'url' => 'https://store.steampowered.com/app/8930/Sid_Meiers_Civilization_V/'],
                    ['title' => 'Fallout: New Vegas on Steam', 'url' => 'https://store.steampowered.com/app/22380/Fallout_New_Vegas/'],
                    ['title' => 'Terraria on Steam', 'url' => 'https://store.steampowered.com/app/105600/Terraria/'],
                    ['title' => 'Skyrim Special Edition on Steam', 'url' => 'https://store.steampowered.com/app/489830/The_Elder_Scrolls_V_Skyrim_Special_Edition/'],
                    ['title' => 'Stardew Valley official site', 'url' => 'https://www.stardewvalley.net/'],
                ]),
            ],
            8 => [
                'name' => 'GrandChase Mobile: phân tích combat, progression và UI',
                'short_description' => 'Case study GrandChase Mobile về điều khiển kéo-thả, đội hình bốn nhân vật, progression, anime presentation và bài học UX.',
                'description' => <<<'HTML'
<p>GrandChase Mobile, được KOG phát hành trên Google Play với tên <strong>GrandChase</strong>, là một case study hữu ích về việc chuyển tinh thần của một thương hiệu action RPG PC sang trải nghiệm đội hình trên màn hình cảm ứng. Đây không phải bài xếp hạng hay cam kết rằng mọi tính năng, sự kiện và gói bán trong game sẽ giữ nguyên. Nội dung được đối chiếu với trang ứng dụng chính thức tại thời điểm biên tập; người chơi nên kiểm tra cửa hàng theo khu vực trước khi tải.</p>

<h2>Bối cảnh: kế thừa thương hiệu, không sao chép nguyên điều khiển</h2>
<p>Trang KOG mô tả GrandChase Classic là action RPG đi cảnh ngang với đồ họa lấy cảm hứng anime. Bản mobile lại đặt trọng tâm vào đội hình, kỹ năng và cách điều hướng phù hợp cảm ứng. Sự khác biệt này minh họa một nguyên tắc quan trọng: chuyển nền tảng không chỉ là thu nhỏ giao diện. Nhà phát triển phải xác định yếu tố nhận diện nào cần giữ—nhân vật, thế giới, nhịp hành động, hiệu ứng—và thao tác nào phải thiết kế lại.</p>
<p>Với một IP có sẵn, người chơi cũ mang theo kỳ vọng còn người mới cần hiểu hệ thống mà không biết lịch sử. Onboarding vì vậy phải làm hai việc cùng lúc: tạo cảm giác quen qua hình ảnh và nhân vật, nhưng không giả định người dùng đã biết vai trò, tài nguyên hoặc đội hình.</p>

<h2>Vòng lặp chiến đấu: quan sát, gom mục tiêu, chọn thời điểm dùng kỹ năng</h2>
<p>Thông tin chính thức trên Google Play nhấn mạnh thao tác chạm và kéo, đội hình bốn nhân vật cùng pet, cũng như quyết định về thời điểm, thứ tự, vị trí và hướng dùng kỹ năng. Vòng lặp cơ bản có thể phân tích thành bốn bước: đọc vị trí kẻ địch; điều hướng đội hình; gom hoặc kiểm soát mục tiêu; rồi sử dụng kỹ năng vào cửa sổ hiệu quả. Sau giao tranh, phần thưởng quay lại progression để mở sức mạnh hoặc lựa chọn mới.</p>
<p>Điểm đáng học là giảm số thao tác liên tục nhưng vẫn giữ quyết định. Mobile action không nhất thiết phải có nhiều nút ảo. Nếu một thao tác kéo đồng thời truyền đạt vị trí và hướng, UI tiết kiệm diện tích mà vẫn tạo kỹ năng người chơi. Tuy nhiên, gesture phải có vùng an toàn, ngưỡng nhận diện rõ và phản hồi ngay khi bắt đầu kéo. Thiếu preview phạm vi hoặc hướng sẽ biến thất bại chiến thuật thành cảm giác điều khiển không chính xác.</p>

<h3>Telegraph và độ dễ đọc</h3>
<p>Khi nhiều nhân vật, quái và hiệu ứng cùng xuất hiện, readability quan trọng hơn độ chi tiết của từng sprite. Silhouette của đòn nguy hiểm, màu vùng ảnh hưởng, âm thanh cảnh báo và thời gian telegraph cần tạo thành một ngôn ngữ nhất quán. Hiệu ứng của người chơi có thể rực rỡ nhưng không nên che tín hiệu sát thương từ boss.</p>
<p>Developer nên kiểm thử trên màn hình nhỏ, độ sáng thấp và thiết bị có khung hình không ổn định. Một telegraph chỉ đọc được ở 60 fps trên màn hình lớn chưa phải thiết kế bền vững. Có thể cung cấp tùy chọn giảm hiệu ứng đồng minh, tăng tương phản vùng nguy hiểm và rung phản hồi có thể tắt.</p>

<h2>Thiết kế đội hình: lựa chọn trước trận ảnh hưởng quyết định trong trận</h2>
<p>Đội hình bốn nhân vật tạo lớp chuẩn bị trước khi chiến đấu. Vai trò, kỹ năng chủ động, khả năng kiểm soát, hồi phục và pet cần bổ sung cho nhau. Đây là chỗ game kết nối collection với strategy: thu thập chỉ có ý nghĩa khi nhân vật mới mở một cách giải quyết khác, không chỉ tạo con số lớn hơn.</p>
<p>Một hệ thống đội hình tốt cho phép người chơi giải thích vì sao họ thắng hoặc thua. Màn hình kết quả nên chỉ ra sát thương nhận, hồi phục, thời điểm bị khống chế hoặc mục tiêu chưa xử lý. Nếu thất bại chỉ dẫn tới lời nhắc “tăng lực chiến”, người chơi sẽ hiểu progression là cánh cổng số thay vì cơ hội học chiến thuật.</p>

<h2>Progression nhiều lớp: lợi ích và rủi ro</h2>
<p>Trang ứng dụng liệt kê các lớp nâng cấp như upgrade, evolve, prestige và awaken. Nhiều trục progression giúp đội hình phát triển dài hạn, đồng thời tạo mục tiêu ngắn cho từng phiên chơi. Nhưng mỗi lớp mới cũng tăng chi phí nhận thức: người mới phải biết tài nguyên nào dùng ở đâu, có hoàn lại được không và lựa chọn có làm hỏng nhân vật hay không.</p>
<p>Để giảm rối, UI nên mở hệ thống theo nhịp sử dụng thực tế, giải thích bằng một nhiệm vụ có ngữ cảnh và giữ tên tài nguyên khác biệt. Màn hình nâng cấp cần hiển thị trước kết quả, nguồn kiếm nguyên liệu và giới hạn. Nếu có yếu tố ngẫu nhiên hoặc mua trong ứng dụng, xác suất, điều kiện bảo đảm và giá trị quy đổi phải minh bạch theo chính sách cửa hàng và pháp luật khu vực.</p>

<h3>Tránh để số lớp nâng cấp thay thế chiều sâu</h3>
<p>Progression lành mạnh nên quay lại làm phong phú combat: mở kỹ năng, biến thể build, synergy hoặc cách xử lý màn chơi. Nếu phần lớn nâng cấp chỉ tăng chỉ số để vượt một ngưỡng lực chiến, người chơi ít có lý do thử chiến thuật. Khi thiết kế live service, team cần theo dõi tỷ lệ hoàn thành theo sức mạnh, thời gian đạt mốc, nguồn tài nguyên bị nghẽn và số lựa chọn đội hình khả dụng.</p>
<p>Catch-up mechanic cũng quan trọng khi roster tăng. Nhân vật mới nhận được nhưng không thể dùng trong thời gian dài vì thiếu tài nguyên sẽ làm phần thưởng mất giá trị. Có thể dùng level sync, hoàn tài nguyên có kiểm soát hoặc nội dung thử nhân vật để người chơi đánh giá trước khi đầu tư.</p>

<h2>Anime presentation: hình ảnh phải phục vụ nhận diện</h2>
<p>Phong cách anime giúp thương hiệu duy trì nhân vật dễ nhận biết qua portrait, model, icon và hoạt cảnh kỹ năng. Tuy nhiên, consistency quan trọng hơn số lượng hiệu ứng. Màu vai trò, khung rarity, trạng thái buff/debuff và portrait cần tuân theo design system; nếu mỗi sự kiện dùng một ngôn ngữ riêng, người chơi sẽ mất thời gian đọc lại UI.</p>
<p>Hoạt cảnh kỹ năng đặc biệt tạo khoảnh khắc thưởng nhưng phải tôn trọng nhịp chơi. Tùy chọn rút gọn hoặc bỏ qua animation lặp lại giúp phiên chơi dài ít mệt. Text localization cần chừa không gian cho tiếng có độ dài khác nhau, tránh nhúng chữ trực tiếp vào hình và kiểm thử font tiếng Việt nếu phát hành tại Việt Nam.</p>

<h2>Mobile UX: ưu tiên thao tác thường xuyên</h2>
<p>Ngón tay che một phần màn hình và không có trạng thái hover, vì vậy control phải dựa vào phản hồi trực tiếp. Nút dùng thường xuyên cần nằm trong vùng với tới, còn hành động chi tiêu tài nguyên hiếm nên có bước xác nhận. Kích thước chạm, khoảng cách giữa nút và safe area phải được thử trên nhiều tỷ lệ màn hình.</p>
<p>Phiên mobile thường bị gián đoạn bởi cuộc gọi, mất mạng hoặc chuyển ứng dụng. Game nên xử lý pause, reconnect và resume mà không làm mất phần thưởng. Với nội dung online, server phải là nguồn sự thật cho giao dịch quan trọng, nhưng client vẫn cần thông báo trạng thái dễ hiểu thay vì spinner vô hạn.</p>

<h3>Onboarding theo hành động</h3>
<p>Tutorial tốt không khóa màn hình để buộc người chơi chạm hàng chục lần. Hãy cho họ thực hiện một quyết định thực: kéo đội hình ra khỏi vùng nguy hiểm, gom mục tiêu rồi dùng kỹ năng đúng hướng. Sau đó, cho phép lặp trong tình huống mới. Tooltip chỉ nên giải thích thông tin mà hình ảnh và phản hồi không thể truyền tải.</p>
<p>Đo lường onboarding bằng thời gian hoàn tất, điểm bỏ cuộc, số lần chạm sai và khả năng người chơi thực hiện lại hành động khi không còn mũi tên chỉ dẫn. Hoàn thành tutorial không đồng nghĩa đã hiểu nếu người dùng chỉ làm theo highlight.</p>

<h2>Khung prototype cho developer</h2>
<ol>
<li>Tạo một arena nhỏ với ba loại mục tiêu: cận chiến, tầm xa và mục tiêu cần ngắt kỹ năng.</li>
<li>Dùng một gesture kéo để điều hướng hoặc xác định vùng kỹ năng; hiển thị preview trước khi thả.</li>
<li>Thiết kế bốn nhân vật có vai trò khác nhau, nhưng chỉ dùng hai tài nguyên chiến đấu chung để giảm phức tạp.</li>
<li>Ghi telemetry cho thời điểm dùng kỹ năng, số mục tiêu trúng, sát thương tránh được và nguyên nhân thất bại.</li>
<li>Test trên thiết bị thật, đặc biệt ở 30 fps và khi ngón tay che vùng trung tâm.</li>
</ol>
<p>Prototype này đủ để kiểm tra câu hỏi cốt lõi: thao tác có chính xác không, quyết định có dễ hiểu không, và đội hình có tạo chiến thuật không. Chưa cần collection lớn, shop hoặc nhiều lớp progression trước khi combat chứng minh được giá trị.</p>

<h2>Kết luận</h2>
<p>GrandChase Mobile đáng nghiên cứu vì cách một IP action RPG được diễn giải thành trải nghiệm đội hình cảm ứng. Giá trị không nằm ở việc sao chép toàn bộ hệ thống mà ở các câu hỏi thiết kế: làm sao giữ nhịp hành động với ít thao tác, làm sao để đội hình ảnh hưởng combat, và làm sao trình bày progression nhiều lớp mà người chơi vẫn hiểu hậu quả lựa chọn.</p>
<p>Với người chơi, hãy kiểm tra trang cửa hàng chính thức để biết khả năng cài đặt, nội dung mua trong ứng dụng, quyền truy cập và cập nhật mới nhất. Với developer, hãy bắt đầu từ một combat loop nhỏ, đo hành vi trên thiết bị thật, rồi mới mở rộng collection và live operations.</p>

<h2>Nguồn tham khảo chính thức</h2>
<ul>
<li><a href="https://play.google.com/store/apps/details?id=com.kog.grandchaseglobal" target="_blank" rel="noopener noreferrer">GrandChase trên Google Play, nhà phát hành KOG CO., LTD.</a> — mô tả điều khiển, đội hình và các lớp progression.</li>
<li><a href="https://www.grandchase.net/" target="_blank" rel="noopener noreferrer">Website chính thức GrandChase</a> — trạng thái và thông tin dịch vụ hiện hành.</li>
<li><a href="https://koggames.com/game/grandchaseclassic" target="_blank" rel="noopener noreferrer">GrandChase Classic trên KOG Games</a> — bối cảnh thương hiệu action RPG PC.</li>
</ul>
HTML,
                'meta_title' => 'GrandChase Mobile: phân tích combat và UX | LamGame',
                'meta_description' => 'Case study GrandChase Mobile về combat kéo-thả, đội hình bốn nhân vật, progression, anime presentation và bài học UX cho developer.',
                'meta_keywords' => 'GrandChase Mobile, GrandChase M, mobile RPG, combat design, progression, mobile UX',
                'sources' => $this->encodeSources([
                    ['title' => 'GrandChase on Google Play', 'url' => 'https://play.google.com/store/apps/details?id=com.kog.grandchaseglobal'],
                    ['title' => 'GrandChase official website', 'url' => 'https://www.grandchase.net/'],
                    ['title' => 'GrandChase Classic by KOG Games', 'url' => 'https://koggames.com/game/grandchaseclassic'],
                ]),
            ],
            76 => [
                'name' => 'Công cụ phát triển game HTML5: hướng dẫn chọn và triển khai',
                'short_description' => 'Tutorial chọn Phaser, PixiJS, PlayCanvas hoặc Godot Web, kèm demo Phaser, checklist hiệu năng, input, audio và quy trình deploy.',
                'description' => <<<'HTML'
<p>Game HTML5 chạy trực tiếp trong trình duyệt, dễ chia sẻ bằng URL và không yêu cầu người chơi cài một gói riêng. Đổi lại, developer phải làm việc trong giới hạn của trình duyệt: bộ nhớ, autoplay audio, input cảm ứng, kích thước tải ban đầu, WebGL và chính sách cache. Tutorial này giúp bạn chọn giữa Phaser, PixiJS, PlayCanvas và Godot Web, sau đó xây một prototype Phaser nhỏ và chuẩn bị bản build để deploy.</p>

<h2>Khi nào nên chọn HTML5?</h2>
<p>HTML5 phù hợp với game 2D casual, mini game marketing, playable demo, game giáo dục, prototype chia sẻ nhanh và trải nghiệm cần nhúng vào website. Một đường dẫn có thể đưa tester vào game trong vài giây, giúp vòng phản hồi ngắn hơn. Web cũng là lựa chọn tốt khi team đã quen JavaScript hoặc TypeScript và cần tích hợp UI, tài khoản hay API của website.</p>
<p>Không nên chọn web chỉ vì nghĩ rằng “không cần tối ưu”. Trình duyệt trên điện thoại có thể đóng tab khi dùng quá nhiều bộ nhớ; mạng di động làm bundle lớn tải chậm; GPU và driver khác nhau tạo lỗi khó tái hiện. Game 3D nặng, cần native SDK sâu hoặc phụ thuộc đa luồng có thể phù hợp hơn với nền tảng native. Hãy thử một vertical slice trên thiết bị yếu nhất trong phạm vi hỗ trợ trước khi quyết định.</p>

<h2>So sánh bốn lựa chọn phổ biến</h2>

<h3>Phaser: framework game 2D có sẵn vòng đời</h3>
<p>Tài liệu chính thức mô tả Phaser là framework HTML5 dành cho game chạy trong trình duyệt desktop hoặc mobile. Phaser cung cấp scene, game object, input, animation, camera, loader, physics và audio trong một cấu trúc thống nhất. Đây là lựa chọn thực dụng khi bạn muốn làm game 2D hoàn chỉnh mà không tự ghép nhiều thư viện nền.</p>
<p><strong>Nên dùng khi:</strong> game có nhiều màn, sprite animation, va chạm, camera hoặc input cần quản lý theo scene. <strong>Cần cân nhắc:</strong> nếu dự án chỉ là một visualization nhỏ, abstraction của engine có thể nhiều hơn nhu cầu. Hãy cố định phiên bản package và đọc đúng tài liệu của phiên bản đó vì API có thể thay đổi.</p>

<h3>PixiJS: renderer 2D linh hoạt</h3>
<p>PixiJS tự giới thiệu là công cụ tạo nội dung HTML5 với renderer 2D WebGPU/WebGL. Trọng tâm của PixiJS là render scene graph, texture, text và hiệu ứng; nó không áp đặt toàn bộ kiến trúc game. Bạn có thể ghép PixiJS với thư viện physics, ECS, audio hoặc UI theo nhu cầu.</p>
<p><strong>Nên dùng khi:</strong> cần renderer 2D hiệu năng cao, interactive content, bản đồ, UI động hoặc team muốn tự kiểm soát game loop. <strong>Cần cân nhắc:</strong> sự linh hoạt đồng nghĩa bạn phải tự quyết định scene management, collision, save state và tooling. Đừng chọn renderer rồi mặc định mọi hệ thống game đã có sẵn.</p>

<h3>PlayCanvas: hướng web-first cho trải nghiệm 3D</h3>
<p>PlayCanvas cung cấp engine, editor trên web, user manual, tutorial và API cho ứng dụng tương tác. Workflow editor giúp artist và developer cùng thiết lập scene, material, light, animation rồi chạy trực tiếp trên trình duyệt. Nó phù hợp với 3D web, configurator, game nhỏ hoặc trải nghiệm cần iteration nhanh giữa nhiều vai trò.</p>
<p><strong>Nên dùng khi:</strong> dự án 3D ưu tiên web, cần editor trực quan và cộng tác. <strong>Cần cân nhắc:</strong> asset 3D, shader, ánh sáng và texture dễ làm thời gian tải tăng mạnh. Cần lập budget draw call, texture memory và dung lượng ngay từ prototype, không đợi tới cuối dự án.</p>

<h3>Godot Web export: dùng cùng project Godot nhưng hiểu giới hạn web</h3>
<p>Godot cho phép export project sang web. Tài liệu stable nêu rằng Godot 4 trên web dùng WebGL 2.0 với Compatibility renderer; Forward+ và Mobile renderer không được hỗ trợ trên nền tảng này. Export single-thread tăng khả năng tương thích trong nhiều bối cảnh, còn cấu hình đa luồng có thể yêu cầu cross-origin isolation và header máy chủ phù hợp.</p>
<p><strong>Nên dùng khi:</strong> team đã xây game trong Godot, cần demo web hoặc game có phạm vi phù hợp Compatibility renderer. <strong>Cần cân nhắc:</strong> không giả định bản desktop có thể export và chạy giống hệt. Hãy kiểm tra plugin, shader, audio, kích thước file và hosting headers từ sớm.</p>

<h2>Bảng quyết định nhanh</h2>
<table>
<thead><tr><th>Nhu cầu chính</th><th>Lựa chọn khởi đầu</th><th>Lý do</th></tr></thead>
<tbody>
<tr><td>Game 2D có scene, input, animation, physics</td><td>Phaser</td><td>Nhiều hệ thống game đã được tổ chức sẵn</td></tr>
<tr><td>Render 2D tùy biến hoặc interactive content</td><td>PixiJS</td><td>Renderer linh hoạt, ít áp đặt kiến trúc</td></tr>
<tr><td>Game/trải nghiệm 3D web-first</td><td>PlayCanvas</td><td>Engine và editor hướng trình duyệt</td></tr>
<tr><td>Project Godot cần bản chơi thử trên web</td><td>Godot Web export</td><td>Giữ workflow Godot nhưng phải theo giới hạn web</td></tr>
</tbody>
</table>
<p>Bảng này là điểm xuất phát, không thay thế prototype. Hãy dành một ngày dựng cùng một scene nhỏ bằng hai ứng viên, đo bundle, thời gian tải, FPS và mức dễ bảo trì. Công cụ “nhanh nhất” trên landing page chưa chắc nhanh nhất với asset và kỹ năng của team bạn.</p>

<h2>Demo Phaser: di chuyển hình vuông bằng bàn phím và pointer</h2>
<p>Ví dụ dưới đây không cần asset ngoài. Nó tạo một player, cho phép di chuyển bằng phím mũi tên/WASD và chạm hoặc click để đặt mục tiêu. Dùng hình học giúp bạn kiểm tra game loop trước khi pipeline hình ảnh hoàn tất.</p>

<pre><code class="language-html">&lt;!doctype html&gt;
&lt;html lang="vi"&gt;
&lt;head&gt;
  &lt;meta charset="utf-8"&gt;
  &lt;meta name="viewport" content="width=device-width,initial-scale=1"&gt;
  &lt;title&gt;Phaser HTML5 demo&lt;/title&gt;
  &lt;style&gt;html,body{margin:0;background:#111827}canvas{display:block;margin:auto}&lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
  &lt;script src="https://cdn.jsdelivr.net/npm/phaser@3.90.0/dist/phaser.min.js"&gt;&lt;/script&gt;
  &lt;script&gt;
    const config = {
      type: Phaser.AUTO,
      width: 800,
      height: 450,
      backgroundColor: '#111827',
      scale: { mode: Phaser.Scale.FIT, autoCenter: Phaser.Scale.CENTER_BOTH },
      scene: { create, update }
    };

    new Phaser.Game(config);

    let player;
    let keys;
    let target;

    function create() {
      player = this.add.rectangle(400, 225, 42, 42, 0x22c55e);
      keys = this.input.keyboard.addKeys('W,A,S,D,UP,DOWN,LEFT,RIGHT');

      this.input.on('pointerdown', (pointer) =&gt; {
        target = new Phaser.Math.Vector2(pointer.worldX, pointer.worldY);
      });
    }

    function update(_time, delta) {
      const speed = 240 * delta / 1000;
      let dx = Number(keys.D.isDown || keys.RIGHT.isDown)
        - Number(keys.A.isDown || keys.LEFT.isDown);
      let dy = Number(keys.S.isDown || keys.DOWN.isDown)
        - Number(keys.W.isDown || keys.UP.isDown);

      if (dx || dy) {
        target = null;
        const direction = new Phaser.Math.Vector2(dx, dy).normalize();
        player.x += direction.x * speed;
        player.y += direction.y * speed;
      } else if (target) {
        const distance = Phaser.Math.Distance.Between(player.x, player.y, target.x, target.y);
        if (distance &lt;= speed) {
          player.setPosition(target.x, target.y);
          target = null;
        } else {
          const angle = Phaser.Math.Angle.Between(player.x, player.y, target.x, target.y);
          player.x += Math.cos(angle) * speed;
          player.y += Math.sin(angle) * speed;
        }
      }

      player.x = Phaser.Math.Clamp(player.x, 21, 779);
      player.y = Phaser.Math.Clamp(player.y, 21, 429);
    }
  &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>

<p>Ví dụ dùng <code>delta</code> thay vì cộng số pixel cố định mỗi frame, vì tốc độ khung hình khác nhau giữa thiết bị. Vector được normalize để di chuyển chéo không nhanh hơn ngang hoặc dọc. Scale FIT giữ tỷ lệ canvas; với game thật, bạn còn phải quyết định vùng an toàn, camera và cách bố trí UI khi màn hình rất dài.</p>
<p>CDN tiện cho demo nhưng production nên cài package bằng npm, khóa phiên bản trong lockfile và bundle bằng Vite hoặc công cụ tương đương. Cách đó giúp tái lập build, kiểm soát cache và tránh phụ thuộc runtime vào CDN không thuộc quyền quản lý của bạn.</p>

<h2>Asset pipeline: giảm tải trước khi tối ưu code</h2>
<ul>
<li><strong>Texture:</strong> dùng atlas để giảm lần chuyển texture, bỏ vùng trong suốt thừa và tạo kích thước phù hợp thiết bị. Không tải texture 4K chỉ để hiển thị 200 px.</li>
<li><strong>Định dạng:</strong> so sánh WebP/AVIF cho hình nền và PNG khi cần workflow tương thích; đo chất lượng thay vì đổi định dạng theo cảm tính.</li>
<li><strong>Audio:</strong> cung cấp định dạng trình duyệt hỗ trợ, cắt khoảng lặng và không preload toàn bộ nhạc nếu màn đầu chưa dùng.</li>
<li><strong>Font:</strong> subset ký tự khi giấy phép cho phép, preload có chọn lọc và luôn có fallback để tránh màn hình trống.</li>
<li><strong>Level data:</strong> tách dữ liệu theo chapter/scene; tải trước nội dung sắp dùng trong lúc người chơi đang ở màn an toàn.</li>
</ul>
<p>Lập budget ngay từ đầu, ví dụ dung lượng tải đầu, texture memory ước tính và thời gian tới tương tác trên mạng chậm. Budget cụ thể phụ thuộc đối tượng; điều quan trọng là đo trên thiết bị thật bằng cold cache, không chỉ localhost.</p>

<h2>Input, responsive và accessibility</h2>
<p>Đừng gắn gameplay trực tiếp vào một thiết bị nhập. Hãy tạo action như <code>moveLeft</code>, <code>confirm</code>, <code>pause</code>, rồi ánh xạ keyboard, pointer, touch hoặc gamepad. Cách này giúp remap phím và test tự động dễ hơn. Trên touch, tránh gesture xung đột với cuộn trang; dùng CSS <code>touch-action</code> đúng phạm vi canvas và vẫn cung cấp đường thoát khỏi fullscreen.</p>
<p>Responsive không chỉ là kéo giãn canvas. Chọn một logical resolution, scale để vừa viewport, xác định safe area cho notch và quyết định phần thế giới được mở rộng hay crop. UI nên neo theo cạnh/safe area còn gameplay giữ hệ tọa độ ổn định. Kiểm thử portrait, landscape, thay đổi orientation giữa trận và browser toolbar làm viewport đổi chiều cao.</p>
<p>Accessibility nên có từ kiến trúc: remap control, không truyền tải thông tin chỉ bằng màu, điều chỉnh âm lượng riêng, tắt rung hoặc screen shake, pause được và font đủ đọc. Với mini game có nội dung bên ngoài canvas, semantic HTML giúp bàn phím và công nghệ hỗ trợ truy cập menu tốt hơn.</p>

<h2>Audio: xử lý chính sách autoplay</h2>
<p>Trình duyệt thường không cho phát audio có tiếng trước tương tác của người dùng. Vì vậy, đừng coi lỗi khởi tạo audio là lỗi ngẫu nhiên. Hiển thị nút “Bắt đầu”, khởi tạo hoặc resume audio context sau click/tap, và lưu lựa chọn mute. Khi tab bị ẩn, có thể giảm hoặc dừng âm thanh; khi quay lại, đồng bộ trạng thái thay vì phát chồng nhiều track.</p>
<p>Nén audio theo nội dung: hiệu ứng ngắn cần độ trễ thấp, nhạc dài cần streaming hoặc tải trì hoãn. Đo trên iOS Safari và Android Chrome vì hành vi lifecycle không hoàn toàn giống desktop.</p>

<h2>Checklist hiệu năng</h2>
<ol>
<li>Đo thời gian tải với cache trống và profile mạng chậm.</li>
<li>Ghi FPS, frame time và memory trên thiết bị mục tiêu thấp nhất.</li>
<li>Giới hạn số object tạo/hủy mỗi frame; dùng pool cho projectile hoặc particle lặp lại.</li>
<li>Giảm overdraw, vùng transparency lớn, filter toàn màn hình và số draw call không cần thiết.</li>
<li>Dùng spatial partition hoặc broad phase phù hợp thay vì kiểm tra mọi cặp va chạm.</li>
<li>Chia việc nặng qua nhiều frame; Web Worker chỉ dùng cho dữ liệu có thể truyền an toàn và sau khi đo bottleneck.</li>
<li>Pause hoặc giảm update khi tab ẩn, nhưng xử lý thời gian quay lại để timer không nhảy sai.</li>
<li>Không tối ưu theo FPS trung bình duy nhất; xem frame spike vì giật ngắn ảnh hưởng cảm giác điều khiển.</li>
</ol>
<p>DevTools Performance, Network và Memory là điểm bắt đầu tốt. Thêm telemetry production ở mức tôn trọng quyền riêng tư để biết thời gian tải, lỗi WebGL và tỷ lệ thiết bị; không thu dữ liệu không cần thiết.</p>

<h2>Build và deploy</h2>
<p>Với dự án JavaScript, dùng npm cùng lockfile, script build tái lập và bundler như Vite. Tách source map khỏi public production nếu chúng chứa mã bạn không muốn công khai, nhưng lưu chúng trong hệ thống theo dõi lỗi. Hash tên asset để cache dài hạn; riêng <code>index.html</code> nên cache ngắn để trỏ tới bundle mới.</p>
<p>Server cần gửi đúng MIME type cho JavaScript, WebAssembly, JSON và font. Bật HTTPS, compression Brotli/Gzip và kiểm tra CORS cho asset khác origin. Service worker/PWA chỉ nên thêm khi team có chiến lược update rõ; cache sai có thể khiến người chơi mắc kẹt ở phiên bản cũ. Luôn có cơ chế phát hiện build mới và reload an toàn ngoài trận.</p>
<p>Godot đa luồng có yêu cầu header bảo mật riêng; hãy theo tài liệu export stable thay vì sao chép cấu hình cũ. Với mọi engine, mở URL deploy thật trên Safari, Chrome, Firefox và thiết bị mobile. Local dev server không mô phỏng đầy đủ cache, header và đường dẫn base.</p>

<h2>Cấu trúc dự án nhỏ đề xuất</h2>
<pre><code>my-html5-game/
├── public/
│   └── assets/
├── src/
│   ├── scenes/
│   ├── input/
│   ├── systems/
│   └── main.js
├── index.html
├── package.json
└── package-lock.json</code></pre>
<p>Scene quản lý vòng đời nội dung, input chuyển thiết bị thành action, systems chứa logic có thể test và <code>main.js</code> chỉ khởi tạo cấu hình. Tránh đặt toàn bộ game trong một file sau prototype; tách theo trách nhiệm giúp thay renderer hoặc thêm test mà không chạm mọi nơi.</p>

<h2>Kết luận</h2>
<p>Chọn Phaser nếu cần framework game 2D đầy đủ; PixiJS nếu ưu tiên renderer và kiến trúc tùy biến; PlayCanvas cho 3D web-first với editor; Godot Web khi muốn đưa project Godot phù hợp lên trình duyệt. Quyết định cuối cùng nên dựa trên một prototype được đo bằng asset thật và thiết bị thật.</p>
<p>Dù dùng công cụ nào, chất lượng bản web phụ thuộc các nền tảng giống nhau: tải đầu nhỏ, frame time ổn định, input độc lập thiết bị, audio tuân thủ autoplay, layout chịu được nhiều viewport, cache có chiến lược và HTTPS đúng. Làm checklist này từ đầu rẻ hơn nhiều so với sửa sau khi game đã có hàng trăm asset.</p>

<h2>Nguồn tài liệu chính thức</h2>
<ul>
<li><a href="https://docs.phaser.io/" target="_blank" rel="noopener noreferrer">Phaser Documentation</a></li>
<li><a href="https://pixijs.com/8.x/guides/getting-started/intro" target="_blank" rel="noopener noreferrer">PixiJS v8 Guides</a></li>
<li><a href="https://developer.playcanvas.com/" target="_blank" rel="noopener noreferrer">PlayCanvas Developer Site</a></li>
<li><a href="https://docs.godotengine.org/en/stable/tutorials/export/exporting_for_web.html" target="_blank" rel="noopener noreferrer">Godot: Exporting for the Web</a></li>
</ul>
HTML,
                'meta_title' => 'Công cụ làm game HTML5: tutorial chọn và deploy | LamGame',
                'meta_description' => 'So sánh Phaser, PixiJS, PlayCanvas, Godot Web; demo Phaser và checklist hiệu năng, input, audio, responsive, build, deploy game HTML5.',
                'meta_keywords' => 'game HTML5, Phaser, PixiJS, PlayCanvas, Godot Web, JavaScript game development',
                'sources' => $this->encodeSources([
                    ['title' => 'Phaser Documentation', 'url' => 'https://docs.phaser.io/'],
                    ['title' => 'PixiJS v8 Guides', 'url' => 'https://pixijs.com/8.x/guides/getting-started/intro'],
                    ['title' => 'PlayCanvas Developer Site', 'url' => 'https://developer.playcanvas.com/'],
                    ['title' => 'Godot: Exporting for the Web', 'url' => 'https://docs.godotengine.org/en/stable/tutorials/export/exporting_for_web.html'],
                ]),
            ],
        ];
    }
}
