<?php

namespace App\Listeners;

use App\Models\LicenseKey;
use App\Models\ProductLicense;
use Illuminate\Support\Facades\DB;
use Webkul\Sales\Models\Order;

/**
 * Sinh License Key cho người mua khi đơn hàng hoàn tất (FEAT-01).
 *
 * Với mỗi order item là sản phẩm downloadable, tạo một license key gắn với
 * (order, product, customer). Idempotent: nếu đã có license cho bộ ba này thì bỏ qua.
 */
class GenerateLicenseOnOrderComplete
{
    public function handle($order)
    {
        if ($order->status !== Order::STATUS_COMPLETED) {
            return;
        }

        $customerId = $order->customer_id;

        if (! $customerId) {
            return; // Khách vãng lai không có tài khoản -> không gắn license
        }

        foreach ($order->items as $item) {
            $product = $item->product;

            if (! $product || $product->type !== 'downloadable') {
                continue;
            }

            // Idempotent: đã có license cho đơn + sản phẩm + khách này thì bỏ qua
            $exists = LicenseKey::where('order_id', $order->id)
                ->where('product_id', $product->id)
                ->where('customer_id', $customerId)
                ->exists();

            if ($exists) {
                continue;
            }

            // Chọn license type: ưu tiên license mặc định của sản phẩm (rẻ nhất/đang bật),
            // nếu sản phẩm chưa cấu hình thì dùng loại "single" (id nhỏ nhất) làm mặc định.
            $licenseTypeId = ProductLicense::where('product_id', $product->id)
                ->where('is_active', true)
                ->orderBy('price')
                ->value('license_type_id');

            if (! $licenseTypeId) {
                $licenseTypeId = \App\Models\LicenseType::orderBy('id')->value('id');
            }

            if (! $licenseTypeId) {
                continue; // Hệ thống chưa có license type nào -> không thể sinh
            }

            DB::transaction(function () use ($product, $licenseTypeId, $customerId, $order) {
                LicenseKey::generate($product->id, $licenseTypeId, $customerId, $order->id);
            });
        }
    }
}
