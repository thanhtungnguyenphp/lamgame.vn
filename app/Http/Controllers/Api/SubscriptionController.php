<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionUsage;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(
        private SubscriptionService $service,
    ) {}

    /**
     * GET /subscription/plans — Danh sách gói
     */
    public function plans(): JsonResponse
    {
        $plans = SubscriptionPlan::active()->get()->map(fn ($p) => [
            'slug'     => $p->slug,
            'name'     => $p->name,
            'price'    => $p->price,
            'currency' => $p->currency,
            'interval' => $p->billing_interval,
            'features' => $p->features,
        ]);

        return response()->json(['status' => 'ok', 'data' => $plans]);
    }

    /**
     * POST /subscription/subscribe — Đăng ký gói
     */
    public function subscribe(Request $request): JsonResponse
    {
        $request->validate(['plan' => 'required|in:free,pro,business']);

        $userId = $request->user()->id;
        $planSlug = $request->input('plan');

        if ($planSlug === 'free') {
            $sub = $this->service->subscribeFree($userId);
            return response()->json([
                'status' => 'ok',
                'data'   => [
                    'subscription_id' => $sub->id,
                    'plan'            => 'free',
                    'status'          => 'active',
                    'approval_url'    => null,
                ],
            ]);
        }

        $result = $this->service->createPaypalSubscription($userId, $planSlug);

        if (!$result) {
            return response()->json([
                'status' => 'error',
                'error'  => ['code' => 'PAYPAL_ERROR', 'message' => 'Không thể tạo subscription trên PayPal.'],
            ], 500);
        }

        return response()->json(['status' => 'ok', 'data' => $result]);
    }

    /**
     * GET /subscription/status — Trạng thái subscription hiện tại
     */
    public function status(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $sub = $this->service->getActiveSubscription($userId);

        if (!$sub) {
            return response()->json([
                'status' => 'ok',
                'data'   => ['plan' => null, 'subscription' => null],
            ]);
        }

        return response()->json([
            'status' => 'ok',
            'data'   => [
                'plan'         => $sub->plan->slug,
                'plan_name'    => $sub->plan->name,
                'price'        => $sub->plan->price,
                'status'       => $sub->status,
                'starts_at'    => $sub->starts_at?->toIso8601String(),
                'ends_at'      => $sub->ends_at?->toIso8601String(),
                'features'     => $sub->plan->features,
            ],
        ]);
    }

    /**
     * POST /subscription/cancel — Hủy subscription
     */
    public function cancel(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $cancelled = $this->service->cancelOnPaypal($userId);

        if (!$cancelled) {
            // Nếu là gói free hoặc không có PayPal subscription
            $sub = $this->service->getActiveSubscription($userId);
            if ($sub) {
                $sub->update(['status' => 'cancelled', 'cancelled_at' => now()]);
                $cancelled = true;
            }
        }

        return response()->json([
            'status' => $cancelled ? 'ok' : 'error',
            'data'   => ['cancelled' => $cancelled],
        ]);
    }

    /**
     * GET /subscription/usage — Quota đã dùng
     */
    public function usage(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $plan = $this->service->getUserPlan($userId);

        if (!$plan) {
            return response()->json([
                'status' => 'ok',
                'data'   => ['plan' => null, 'usage' => []],
            ]);
        }

        $period = now()->format('Y-m');
        $usages = SubscriptionUsage::where('user_id', $userId)
            ->where('period', $period)
            ->pluck('used', 'feature')
            ->toArray();

        $features = [];
        foreach ($plan->features as $feature => $limit) {
            $used = $usages[$feature] ?? 0;
            $features[$feature] = [
                'limit'     => $limit,
                'used'      => is_bool($limit) ? null : $used,
                'remaining' => is_bool($limit) ? null : ($limit === -1 ? -1 : max(0, $limit - $used)),
            ];
        }

        return response()->json([
            'status' => 'ok',
            'data'   => [
                'plan'   => $plan->slug,
                'period' => $period,
                'usage'  => $features,
            ],
        ]);
    }

    /**
     * GET /subscription/paypal/return — PayPal redirect sau approve
     */
    public function paypalReturn(Request $request): JsonResponse
    {
        $subscriptionId = $request->query('subscription_id');

        if ($subscriptionId) {
            $sub = $this->service->activateSubscription($subscriptionId);
        }

        return response()->json([
            'status' => 'ok',
            'data'   => ['message' => 'Subscription activated successfully.'],
        ]);
    }

    /**
     * POST /subscription/webhook — PayPal webhook
     */
    public function webhook(Request $request): JsonResponse
    {
        $this->service->handleWebhook($request->all());
        return response()->json(['status' => 'ok']);
    }
}
