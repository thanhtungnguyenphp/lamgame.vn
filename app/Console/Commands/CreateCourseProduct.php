<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Core\Models\Channel;
use Webkul\Core\Models\Locale;
use Carbon\Carbon;
use DB;

class CreateCourseProduct extends Command
{
    protected $signature = 'create:course-product';
    protected $description = 'Tạo sản phẩm khóa học chăm sóc cổ vai gáy & mắt';

    public function handle()
    {
        $this->info('📦 Tạo sản phẩm khóa học CHĂM SÓC CỔ VAI GÁY & CHĂM SÓC MẮT...');
        $this->newLine();

        try {
            $productRepository = app(ProductRepository::class);
            $channel = Channel::first();
            $locale = Locale::where('code', 'vi')->first() ?? Locale::first();

            // Create basic product
            $productData = [
                'type' => 'simple',
                'attribute_family_id' => 1,
                'sku' => 'KHOA-HOC-CO-VAI-GAY-MAT-' . time(),
            ];

            $this->info('✨ Tạo sản phẩm cơ bản...');
            $product = $productRepository->create($productData);

            if (!$product) {
                $this->error('❌ Không thể tạo sản phẩm!');
                return 1;
            }

            $this->info("✅ Tạo sản phẩm thành công với ID: {$product->id}");

            // Update product attributes via direct database
            $this->info('📝 Cập nhật thông tin sản phẩm...');
            
            // Update product table
            DB::table('products')->where('id', $product->id)->update([
                'updated_at' => now()
            ]);

            // Insert product flat data
            DB::table('product_flat')->insert([
                'product_id' => $product->id,
                'sku' => $product->sku,
                'type' => 'simple',
                'locale' => $locale->code,
                'channel' => $channel->code,
                'attribute_family_id' => 1,
                'name' => 'KHÓA HỌC CHĂM SÓC CỔ VAI GÁY & CHĂM SÓC MẮT',
                'url_key' => 'khoa-hoc-cham-soc-co-vai-gay-mat-' . $product->id,
                'short_description' => 'Khóa học thực hành 100% từ Em Saigon Beauty & Wellness. Học kỹ thuật chuyên sâu chăm sóc cổ vai gáy & mắt với thiết bị cao cấp.',
                'description' => '<div class="course-details">
                    <h2>🎓 KHÓA HỌC CHĂM SÓC CỔ VAI GÁY & CHĂM SÓC MẮT</h2>
                    
                    <div class="course-highlights">
                        <h3>🌟 Điểm nổi bật</h3>
                        <ul>
                            <li>✅ Thực hành 100% trên khách hàng thật</li>
                            <li>✅ Kỹ thuật chuyên sâu từ chuyên gia</li>
                            <li>✅ Thiết bị chăm sóc Body cao cấp</li>
                            <li>✅ Giải tắc cơ sâu và dẫn lưu hệ bạch huyết</li>
                            <li>✅ Có thể "làm nghề" ngay sau khi hoàn thành</li>
                        </ul>
                    </div>

                    <div class="pricing-section">
                        <h3>💰 Học phí & Ưu đãi đặc biệt</h3>
                        <div class="pricing-table">
                            <p><strong>Học phí gốc:</strong> <span style="text-decoration: line-through;">50.000.000 VNĐ</span></p>
                            <p><strong>🔥 Ưu đãi 50%:</strong> <span style="color: #e74c3c; font-weight: bold; font-size: 1.2em;">25.000.000 VNĐ</span></p>
                            <p><strong>👥 Đăng ký nhóm 3 người:</strong> Giảm thêm 10% → <span style="color: #27ae60; font-weight: bold;">22.500.000 VNĐ/người</span></p>
                        </div>
                    </div>

                    <div class="gifts-section">
                        <h3>🎁 NHẬN NGAY BỘ QUÀ TẶNG TRỊ GIÁ 25 TRIỆU</h3>
                        <div class="gift-items">
                            <p>🔧 <strong>Thiết bị chăm sóc Body cao cấp</strong> - Giúp giải tắc cơ sâu và dẫn lưu hệ bạch huyết</p>
                            <p>🛠️ <strong>Toàn bộ dụng cụ & sản phẩm</strong> - Phục vụ cho khóa học & "làm nghề" sau khi kết thúc</p>
                        </div>
                    </div>

                    <div class="cta-section">
                        <h3>📞 Liên hệ đăng ký ngay</h3>
                        <p><strong>Số lượng có hạn - Ưu đãi chỉ còn trong thời gian giới hạn!</strong></p>
                    </div>
                </div>',
                'meta_title' => 'Khóa học Chăm sóc Cổ Vai Gáy & Mắt - Em Saigon Beauty',
                'meta_description' => 'Khóa học thực hành 100% chăm sóc cổ vai gáy & mắt. Ưu đãi 50% học phí, tặng thiết bị 25 triệu. Đăng ký ngay!',
                'meta_keywords' => 'khóa học, chăm sóc cổ vai gáy, chăm sóc mắt, massage, em saigon, beauty wellness',
                'price' => 25000000.0000,
                'special_price' => 22500000.0000,
                'special_price_from' => Carbon::now()->toDateString(),
                'special_price_to' => Carbon::now()->addMonths(3)->toDateString(),
                'status' => 1,
                'visible_individually' => 1,
                'weight' => 0.0000,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert product attribute values
            $this->info('💰 Thiết lập giá và thuộc tính...');
            
            // Get common attributes
            $priceAttr = DB::table('attributes')->where('code', 'price')->first();
            $costAttr = DB::table('attributes')->where('code', 'cost')->first();
            $specialPriceAttr = DB::table('attributes')->where('code', 'special_price')->first();
            $statusAttr = DB::table('attributes')->where('code', 'status')->first();
            $visibleAttr = DB::table('attributes')->where('code', 'visible_individually')->first();
            
            // Insert attribute values
            if ($priceAttr) {
                DB::table('product_attribute_values')->insert([
                    'product_id' => $product->id,
                    'attribute_id' => $priceAttr->id,
                    'channel' => $channel->code,
                    'locale' => $locale->code,
                    'float_value' => 25000000.0000,
                ]);
            }
            
            if ($costAttr) {
                DB::table('product_attribute_values')->insert([
                    'product_id' => $product->id,
                    'attribute_id' => $costAttr->id,
                    'channel' => $channel->code,
                    'locale' => $locale->code,
                    'float_value' => 50000000.0000,
                ]);
            }
            
            if ($specialPriceAttr) {
                DB::table('product_attribute_values')->insert([
                    'product_id' => $product->id,
                    'attribute_id' => $specialPriceAttr->id,
                    'channel' => $channel->code,
                    'locale' => $locale->code,
                    'float_value' => 22500000.0000,
                ]);
            }
            
            if ($statusAttr) {
                DB::table('product_attribute_values')->insert([
                    'product_id' => $product->id,
                    'attribute_id' => $statusAttr->id,
                    'channel' => $channel->code,
                    'locale' => $locale->code,
                    'boolean_value' => 1,
                ]);
            }
            
            if ($visibleAttr) {
                DB::table('product_attribute_values')->insert([
                    'product_id' => $product->id,
                    'attribute_id' => $visibleAttr->id,
                    'channel' => $channel->code,
                    'locale' => $locale->code,
                    'boolean_value' => 1,
                ]);
            }

            // Assign to categories (check if not exists)
            $categories = [2, 10]; // Khóa học categories
            foreach ($categories as $categoryId) {
                $existingCategory = DB::table('product_categories')
                    ->where('product_id', $product->id)
                    ->where('category_id', $categoryId)
                    ->first();
                    
                if (!$existingCategory) {
                    DB::table('product_categories')->insert([
                        'product_id' => $product->id,
                        'category_id' => $categoryId
                    ]);
                }
            }

            // Assign to channels (check if not exists)
            $existingChannel = DB::table('product_channels')
                ->where('product_id', $product->id)
                ->where('channel_id', $channel->id)
                ->first();
                
            if (!$existingChannel) {
                DB::table('product_channels')->insert([
                    'product_id' => $product->id,
                    'channel_id' => $channel->id
                ]);
            }

            $this->newLine();
            $this->info('✅ Sản phẩm đã được tạo hoàn chỉnh!');
            $this->table(['Field', 'Value'], [
                ['ID', $product->id],
                ['SKU', $product->sku],
                ['Tên', 'KHÓA HỌC CHĂM SÓC CỔ VAI GÁY & CHĂM SÓC MẮT'],
                ['Giá', number_format(25000000, 0, '.', ',') . ' VNĐ'],
                ['Giá ưu đãi nhóm', number_format(22500000, 0, '.', ',') . ' VNĐ'],
                ['Số lượng', '20 suất']
            ]);

            $this->newLine();
            $this->info('🌐 Truy cập sản phẩm tại:');
            $this->line("   - Frontend: http://localhost:8080/product/khoa-hoc-cham-soc-co-vai-gay-mat-{$product->id}");
            $this->line("   - Admin: http://localhost:8080/admin/catalog/products/{$product->id}/edit");

            $this->newLine();
            $this->info('💡 Gợi ý tiếp theo:');
            $this->line('   1. Upload hình ảnh cho sản phẩm qua admin panel');
            $this->line('   2. Kiểm tra và điều chỉnh thông tin nếu cần');
            $this->line('   3. Test checkout process');

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Lỗi: ' . $e->getMessage());
            $this->line('📍 File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            return 1;
        }
    }
}
