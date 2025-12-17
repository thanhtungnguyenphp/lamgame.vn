<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSeller
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('customer')->check()) {
            return redirect('/auth/login')
                ->with('warning', 'Vui lòng đăng nhập.');
        }

        $customer = Auth::guard('customer')->user();
        $seller = $customer->seller;

        if (!$seller) {
            return redirect()->route('seller.register')
                ->with('warning', 'Bạn cần đăng ký seller trước.');
        }

        if ($seller->isPending()) {
            return redirect()->route('seller.pending')
                ->with('info', 'Tài khoản seller đang chờ duyệt.');
        }

        if (!$seller->isActive()) {
            return redirect()->route('seller.pending')
                ->with('error', 'Tài khoản seller chưa được kích hoạt.');
        }

        return $next($request);
    }
}
