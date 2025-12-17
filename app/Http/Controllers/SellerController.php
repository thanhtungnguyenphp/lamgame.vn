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

        return view('seller.register', [
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

        return view('seller.pending', [
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

        $stats = [
            'total_products' => $seller->total_products,
            'total_sales' => $seller->total_sales,
            'total_revenue' => $seller->total_revenue,
            'rating_avg' => $seller->rating_avg,
        ];

        return view('seller.dashboard', [
            'seller' => $seller,
            'stats' => $stats,
            'page_title' => 'Dashboard - Seller - Làm Game',
        ]);
    }
}
