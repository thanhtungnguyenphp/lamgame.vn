<?php

namespace App\Http\Controllers;

use App\Models\SourceGameSeller;
use App\Mail\NewSellerRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SellerController extends Controller
{
    public function showRegisterForm()
    {
        if (!Auth::guard('customer')->check()) {
            return redirect('/auth/login')
                ->with('warning', 'Vui lòng đăng nhập trước khi đăng ký seller.');
        }

        $customer = Auth::guard('customer')->user();
        $seller = $customer->seller;

        // Debug
        \Log::info('Customer ID: ' . $customer->id);
        \Log::info('Seller exists: ' . ($seller ? 'Yes - ' . $seller->shop_name : 'No'));

        return view('shop::seller.register', [
            'customer' => $customer,
            'seller' => $seller,
            'isEdit' => $seller ? true : false,
            'page_title' => $seller ? 'Cập nhật thông tin Seller - Làm Game' : 'Đăng ký Seller - Làm Game',
        ]);
    }

    public function register(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $seller = $customer->seller;
        $isEdit = $seller ? true : false;

        $validated = $request->validate([
            'shop_name' => $isEdit 
                ? 'required|string|max:255|unique:source_game_sellers,shop_name,' . $seller->id
                : 'required|string|max:255|unique:source_game_sellers',
            'shop_description' => 'nullable|string|max:1000',
            'shop_logo' => 'nullable|image|max:2048',
            'shop_banner' => 'nullable|image|max:5120',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'business_type' => 'required|in:individual,company',
            'tax_id' => 'required_if:business_type,company|nullable|string|max:50',
            'bank_name' => 'required|string|max:255',
            'bank_account' => 'required|string|max:100',
            'bank_holder' => 'required|string|max:255',
            'terms_accepted' => $isEdit ? 'nullable' : 'required|accepted',
        ]);

        // Handle file uploads
        $logoPath = $seller ? $seller->shop_logo : null;
        if ($request->hasFile('shop_logo')) {
            if ($seller && $seller->shop_logo) {
                Storage::disk('public')->delete($seller->shop_logo);
            }
            $logoPath = $request->file('shop_logo')->store('seller/logos', 'public');
        }

        $bannerPath = $seller ? $seller->shop_banner : null;
        if ($request->hasFile('shop_banner')) {
            if ($seller && $seller->shop_banner) {
                Storage::disk('public')->delete($seller->shop_banner);
            }
            $bannerPath = $request->file('shop_banner')->store('seller/banners', 'public');
        }

        $data = [
            'shop_name' => $validated['shop_name'],
            'shop_slug' => Str::slug($validated['shop_name']),
            'shop_description' => $validated['shop_description'],
            'shop_logo' => $logoPath,
            'shop_banner' => $bannerPath,
            'contact_email' => $validated['contact_email'],
            'contact_phone' => $validated['contact_phone'],
            'website' => $validated['website'],
            'business_type' => $validated['business_type'],
            'tax_id' => $validated['tax_id'],
            'bank_name' => $validated['bank_name'],
            'bank_account' => $validated['bank_account'],
            'bank_holder' => $validated['bank_holder'],
        ];

        if ($isEdit) {
            // Update existing seller
            $seller->update($data);
            $message = 'Cập nhật thông tin seller thành công!';
        } else {
            // Create new seller
            $data['customer_id'] = $customer->id;
            $data['status'] = 'pending';
            $seller = SourceGameSeller::create($data);
            $message = 'Đăng ký seller thành công! Chúng tôi sẽ xem xét và phản hồi trong vòng 24-48 giờ.';

            // Send notification to admin
            try {
                $adminEmail = config('mail.admin_email', 'admin@lamgame.vn');
                Mail::to($adminEmail)->send(new NewSellerRegistration($seller));
            } catch (\Exception $e) {
                \Log::error('Failed to send admin notification: ' . $e->getMessage());
            }
        }

        return redirect()->route($seller->isActive() ? 'seller.dashboard' : 'seller.pending')
            ->with('success', $message);
    }

    public function pending()
    {
        $customer = Auth::guard('customer')->user();
        $seller = $customer->seller;

        if (!$seller) {
            return redirect()->route('seller.register');
        }

        if ($seller->isActive()) {
            return redirect()->route('seller.dashboard');
        }

        return view('shop::seller.pending', [
            'seller' => $seller,
            'page_title' => 'Đang chờ duyệt - Làm Game',
        ]);
    }

    public function dashboard()
    {
        $customer = Auth::guard('customer')->user();
        $seller = $customer->seller;

        if (!$seller || !$seller->isActive()) {
            return redirect()->route('seller.pending')
                ->with('error', 'Tài khoản seller chưa được kích hoạt.');
        }

        // Calculate stats realtime
        $totalProducts = \DB::table('products')
            ->where('seller_id', $seller->id)
            ->count();

        $salesData = \DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.seller_id', $seller->id)
            ->whereIn('orders.status', ['completed', 'processing', 'pending'])
            ->select(
                \DB::raw('COUNT(DISTINCT orders.id) as total_orders'),
                \DB::raw('SUM(order_items.total) as total_revenue')
            )
            ->first();

        $stats = [
            'total_products' => $totalProducts,
            'total_sales' => $salesData->total_orders ?? 0,
            'total_revenue' => $salesData->total_revenue ?? 0,
            'rating_avg' => $seller->rating_avg ?? 0,
            'available_balance' => $this->getAvailableBalance($seller),
        ];

        // Recent orders
        $recentOrders = \DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.seller_id', $seller->id)
            ->select('orders.*', 'order_items.product_id', 'order_items.name as product_name', 'order_items.total')
            ->orderBy('orders.created_at', 'desc')
            ->limit(10)
            ->get();

        // Top products
        $topProducts = \DB::table('products')
            ->leftJoin('product_flat', 'products.id', '=', 'product_flat.product_id')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->where('products.seller_id', $seller->id)
            ->select('products.id', 'product_flat.name', \DB::raw('COUNT(order_items.id) as sales_count'), \DB::raw('SUM(order_items.total) as revenue'))
            ->groupBy('products.id', 'product_flat.name')
            ->orderBy('sales_count', 'desc')
            ->limit(5)
            ->get();

        // Monthly revenue (last 6 months)
        $monthlyRevenue = \DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.seller_id', $seller->id)
            ->where('orders.created_at', '>=', now()->subMonths(6))
            ->select(\DB::raw('DATE_FORMAT(orders.created_at, "%Y-%m") as month'), \DB::raw('SUM(order_items.total) as revenue'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('shop::seller.dashboard', [
            'seller' => $seller,
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'topProducts' => $topProducts,
            'monthlyRevenue' => $monthlyRevenue,
            'page_title' => 'Dashboard - Seller - Làm Game',
        ]);
    }

    private function getAvailableBalance($seller)
    {
        $totalEarnings = \DB::table('source_game_earnings')
            ->where('seller_id', $seller->id)
            ->where('status', 'completed')
            ->sum('seller_amount');

        $totalWithdrawn = \DB::table('source_game_withdrawals')
            ->where('seller_id', $seller->id)
            ->where('status', 'completed')
            ->sum('amount');

        return $totalEarnings - $totalWithdrawn;
    }

    public function orders()
    {
        $customer = Auth::guard('customer')->user();
        $seller = $customer->seller;

        if (!$seller || !$seller->isActive()) {
            return redirect()->route('seller.pending');
        }

        $orders = \DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.seller_id', $seller->id)
            ->select('orders.*', 'order_items.product_id', 'order_items.name as product_name', 'order_items.qty_ordered as qty', 'order_items.total')
            ->orderBy('orders.created_at', 'desc')
            ->paginate(20);

        return view('shop::seller.orders.index', [
            'seller' => $seller,
            'orders' => $orders,
            'page_title' => 'Đơn hàng - Seller - Làm Game',
        ]);
    }

    public function orderShow($id)
    {
        $customer = Auth::guard('customer')->user();
        $seller = $customer->seller;

        $order = \DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.seller_id', $seller->id)
            ->where('orders.id', $id)
            ->select('orders.*', 'order_items.*')
            ->first();

        if (!$order) {
            abort(404);
        }

        return view('shop::seller.orders.show', [
            'seller' => $seller,
            'order' => $order,
            'page_title' => 'Chi tiết đơn hàng - Seller - Làm Game',
        ]);
    }

    public function analytics()
    {
        $customer = Auth::guard('customer')->user();
        $seller = $customer->seller;

        if (!$seller || !$seller->isActive()) {
            return redirect()->route('seller.pending');
        }

        // Monthly revenue (last 12 months)
        $monthlyRevenue = \DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.seller_id', $seller->id)
            ->where('orders.created_at', '>=', now()->subMonths(12))
            ->select(\DB::raw('DATE_FORMAT(orders.created_at, "%Y-%m") as month'), \DB::raw('SUM(order_items.total) as revenue'), \DB::raw('COUNT(DISTINCT orders.id) as orders_count'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top products (top 10)
        $topProducts = \DB::table('products')
            ->leftJoin('product_flat', 'products.id', '=', 'product_flat.product_id')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->where('products.seller_id', $seller->id)
            ->select('products.id', 'product_flat.name', \DB::raw('COUNT(order_items.id) as sales_count'), \DB::raw('SUM(order_items.total) as revenue'))
            ->groupBy('products.id', 'product_flat.name')
            ->orderBy('sales_count', 'desc')
            ->limit(10)
            ->get();

        // Category breakdown
        $categoryStats = \DB::table('products')
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->join('category_translations', 'product_categories.category_id', '=', 'category_translations.category_id')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->where('products.seller_id', $seller->id)
            ->select('category_translations.name as category_name', \DB::raw('COUNT(DISTINCT products.id) as products_count'), \DB::raw('COUNT(order_items.id) as sales_count'), \DB::raw('SUM(order_items.total) as revenue'))
            ->groupBy('category_translations.name')
            ->get();

        return view('shop::seller.analytics', [
            'seller' => $seller,
            'monthlyRevenue' => $monthlyRevenue,
            'topProducts' => $topProducts,
            'categoryStats' => $categoryStats,
            'page_title' => 'Phân tích - Seller - Làm Game',
        ]);
    }
}
