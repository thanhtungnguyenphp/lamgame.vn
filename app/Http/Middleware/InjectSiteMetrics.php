<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\SiteMetricsService;
use Illuminate\Support\Facades\View;

class InjectSiteMetrics
{
    protected $metricsService;

    public function __construct(SiteMetricsService $metricsService)
    {
        $this->metricsService = $metricsService;
    }

    public function handle(Request $request, Closure $next)
    {
        // Share metrics with all views
        View::share('siteMetrics', $this->metricsService->getMetrics());

        // Tiếp tục luồng mua gói AI sau khi khách đăng nhập:
        // Nếu còn gói đang chờ (pending_plan) và khách đã đăng nhập, đưa họ quay lại
        // trang đăng ký gói để hoàn tất thanh toán. Chỉ áp dụng cho điều hướng GET
        // thông thường, bỏ qua chính route subscribe/login/paypal và các request ajax.
        if (
            $request->isMethod('get')
            && ! $request->ajax()
            && auth()->guard('customer')->check()
            && ($pendingPlan = $request->session()->get('pending_plan'))
            && ! $request->is('ai-tools/subscribe*', 'customer/*', 'api/*')
        ) {
            $request->session()->forget('pending_plan');

            return redirect()->route('lamgame.ai-subscribe', ['plan' => $pendingPlan]);
        }

        return $next($request);
    }
}
