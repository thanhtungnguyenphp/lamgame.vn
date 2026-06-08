<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webkul\Admin\Helpers\Reporting\Customer;
use Webkul\Admin\Helpers\Reporting\Product;
use Webkul\Admin\Helpers\Reporting\Sale;

class ReportingManageController extends Controller
{
    public function __construct(
        protected Sale $saleReporting,
        protected Product $productReporting,
        protected Customer $customerReporting
    ) {}

    public function sales(Request $request): JsonResponse
    {
        $period = $request->input('period', 'month'); // day, week, month, year

        $stats = [
            'total_orders' => $this->saleReporting->getTotalOrdersProgress(),
            'total_sales' => $this->saleReporting->getTotalSalesProgress(),
            'avg_order_value' => $this->saleReporting->getAverageSalesProgress(),
            'total_pending_invoices' => DB::table('invoices')->where('state', 'pending')->sum('grand_total'),
            'orders_by_status' => DB::table('orders')
                ->selectRaw("status, COUNT(*) as count, SUM(grand_total) as total")
                ->groupBy('status')->get(),
            'payment_methods' => DB::table('order_payment')
                ->selectRaw("method, COUNT(*) as count")
                ->groupBy('method')->get(),
        ];

        return response()->json(['status' => 'success', 'data' => $stats]);
    }

    public function customers(Request $request): JsonResponse
    {
        $stats = [
            'total_customers' => $this->customerReporting->getTotalCustomersProgress(),
            'today_customers' => $this->customerReporting->getTodayCustomersProgress(),
            'top_customers' => DB::table('orders')
                ->selectRaw("customer_id, customer_email, CONCAT(customer_first_name, ' ', customer_last_name) as name, COUNT(*) as orders_count, SUM(grand_total) as total_spent")
                ->whereNotNull('customer_id')
                ->groupBy('customer_id', 'customer_email', 'customer_first_name', 'customer_last_name')
                ->orderByDesc('total_spent')
                ->limit(20)->get(),
            'customers_by_group' => DB::table('customers')
                ->join('customer_groups', 'customers.customer_group_id', '=', 'customer_groups.id')
                ->selectRaw("customer_groups.name as group_name, COUNT(*) as count")
                ->groupBy('customer_groups.name')->get(),
        ];

        return response()->json(['status' => 'success', 'data' => $stats]);
    }

    public function products(Request $request): JsonResponse
    {
        $stats = [
            'total_products' => DB::table('product_flat')->where('locale', 'vi')->count(),
            'active_products' => DB::table('product_flat')->where('locale', 'vi')->where('status', 1)->count(),
            'top_selling' => DB::table('order_items')
                ->selectRaw("product_id, name, sku, SUM(qty_ordered) as qty_sold, SUM(total) as revenue")
                ->groupBy('product_id', 'name', 'sku')
                ->orderByDesc('qty_sold')
                ->limit(20)->get(),
            'low_stock' => DB::table('product_inventories')
                ->join('product_flat', 'product_inventories.product_id', '=', 'product_flat.product_id')
                ->where('product_flat.locale', 'vi')
                ->where('product_inventories.qty', '<=', 10)
                ->select('product_flat.product_id', 'product_flat.name', 'product_flat.sku', 'product_inventories.qty')
                ->orderBy('product_inventories.qty')
                ->limit(20)->get(),
        ];

        return response()->json(['status' => 'success', 'data' => $stats]);
    }
}
