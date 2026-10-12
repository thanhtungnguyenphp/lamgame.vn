<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * GĐ6 — Tạo bản dịch tiếng Đức (locale "de") cho product_flat.
 *
 * Catalog chỉ hiển thị sản phẩm có row product_flat đúng locale. Trước đây chỉ có
 * vi/en nên khách Đức xem catalog bị trống. Seeder này nhân bản mỗi sản phẩm từ
 * bản "en" sang locale "de" (giữ nguyên giá, trạng thái, visibility...).
 *
 * Idempotent: chỉ tạo row "de" cho sản phẩm CHƯA có row "de".
 * Nội dung mô tả giữ như bản "en" (có thể chỉnh/dịch tiếp qua admin).
 */
class GermanProductFlatSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('product_flat')) {
            $this->command?->warn('Bảng product_flat không tồn tại — bỏ qua.');
            return;
        }

        // Các (product_id, channel) đã có bản "de" → không tạo lại
        $existing = DB::table('product_flat')
            ->where('locale', 'de')
            ->get(['product_id', 'channel'])
            ->map(fn ($r) => $r->product_id . '|' . $r->channel)
            ->flip();

        $cols = array_values(array_filter(
            Schema::getColumnListing('product_flat'),
            fn ($c) => $c !== 'id'
        ));

        $source = DB::table('product_flat')->where('locale', 'en')->get();
        $created = 0;

        foreach ($source as $row) {
            $key = $row->product_id . '|' . $row->channel;
            if ($existing->has($key)) {
                continue;
            }

            $data = [];
            foreach ($cols as $c) {
                $data[$c] = $c === 'locale' ? 'de' : ($row->$c ?? null);
            }

            DB::table('product_flat')->insert($data);
            $created++;
        }

        $this->command?->info("GermanProductFlatSeeder: đã tạo {$created} row locale=de.");
    }
}
