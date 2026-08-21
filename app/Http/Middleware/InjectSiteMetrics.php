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
        
        return $next($request);
    }
}
