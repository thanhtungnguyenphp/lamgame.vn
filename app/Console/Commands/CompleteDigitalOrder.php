<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Repositories\InvoiceRepository;

/**
 * Hoàn tất thủ công đơn hàng digital (downloadable) đang kẹt ở pending bằng
 * cách tạo invoice đầy đủ. Khi invoice xong, Bagisto tự chuyển order sang
 * 'completed' (vì hàng downloadable không cần ship) -> kích hoạt listener sinh
 * license key + earning cho seller.
 *
 * DÙNG CHO: xử lý đơn PayPal lỡ kẹt, hoặc đơn cần duyệt thủ công.
 * KHÔNG tự chạy định kỳ để tránh giao hàng khi chưa thu tiền.
 *
 * Ví dụ:
 *   php artisan order:complete-digital 7          # hoàn tất đơn #7
 *   php artisan order:complete-digital --dry-run  # xem các đơn đủ điều kiện
 */
class CompleteDigitalOrder extends Command
{
    protected $signature = 'order:complete-digital
        {order? : ID đơn hàng cần hoàn tất}
        {--dry-run : Chỉ liệt kê đơn đủ điều kiện, không thay đổi}';

    protected $description = 'Tạo invoice để hoàn tất đơn downloadable đang pending (kích hoạt sinh license + earning)';

    public function handle(InvoiceRepository $invoiceRepository): int
    {
        $orderId = $this->argument('order');
        $dryRun = (bool) $this->option('dry-run');

        $query = Order::where('status', 'pending');

        if ($orderId) {
            $query->where('id', $orderId);
        }

        $orders = $query->get()->filter(function ($order) {
            // Chỉ đơn có thể invoice và không có hàng cần ship (hàng số thuần)
            return $order->canInvoice() && ! $order->haveStockableItems();
        });

        if ($orders->isEmpty()) {
            $this->warn('Không có đơn downloadable pending nào đủ điều kiện.');

            return self::SUCCESS;
        }

        $this->table(
            ['ID', 'Status', 'Customer', 'Total', 'canInvoice'],
            $orders->map(fn ($o) => [
                $o->id, $o->status, $o->customer_id, $o->grand_total, $o->canInvoice() ? 'yes' : 'no',
            ])->toArray()
        );

        if ($dryRun) {
            $this->info('[dry-run] Không thực hiện thay đổi.');

            return self::SUCCESS;
        }

        if ($orderId === null && ! $this->confirm('Hoàn tất TẤT CẢ đơn trên? (chỉ làm khi đã xác nhận thu tiền)')) {
            $this->info('Đã hủy.');

            return self::SUCCESS;
        }

        $done = 0;

        foreach ($orders as $order) {
            $invoiceData = ['order_id' => $order->id];

            foreach ($order->items as $item) {
                $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
            }

            $invoiceRepository->create($invoiceData);

            $order->refresh();
            $this->line("Đơn #{$order->id} -> status: {$order->status}");
            $done++;
        }

        $this->info("Hoàn tất {$done} đơn. License + earning được sinh qua listener order-complete.");

        return self::SUCCESS;
    }
}
