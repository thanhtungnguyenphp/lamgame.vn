<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionService;
use Closure;
use Illuminate\Http\Request;

class CheckSubscriptionQuota
{
    public function __construct(
        private SubscriptionService $service,
    ) {}

    /**
     * Usage: middleware('quota:feature_name')
     * Ví dụ: Route::post('/ai/concept', ...)->middleware('quota:ai_concept');
     */
    public function handle(Request $request, Closure $next, string $feature)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'error'  => ['code' => 'AUTH_REQUIRED', 'message' => 'Vui lòng đăng nhập.'],
            ], 401);
        }

        $quota = $this->service->checkQuota($user->id, $feature);

        if (!$quota['allowed']) {
            return response()->json([
                'status' => 'error',
                'error'  => [
                    'code'    => 'QUOTA_EXCEEDED',
                    'message' => 'Bạn đã hết quota cho tính năng này. Vui lòng nâng cấp gói.',
                    'quota'   => $quota,
                ],
            ], 403);
        }

        // Consume quota sau khi request thành công
        $request->attributes->set('subscription_feature', $feature);

        $response = $next($request);

        // Chỉ consume nếu response thành công
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            $this->service->useQuota($user->id, $feature);
        }

        return $response;
    }
}
