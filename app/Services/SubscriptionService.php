<?php

namespace App\Services;

use App\Models\SubscriptionPlan;
use App\Models\SubscriptionTransaction;
use App\Models\SubscriptionUsage;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SubscriptionService
{
    /**
     * Lấy subscription active của user. Null = chưa subscribe.
     */
    public function getActiveSubscription(int $userId): ?UserSubscription
    {
        return UserSubscription::forUser($userId)
            ->active()
            ->with('plan')
            ->latest('id')
            ->first();
    }

    /**
     * Lấy plan hiện tại của user (default: null = no plan).
     */
    public function getUserPlan(int $userId): ?SubscriptionPlan
    {
        return $this->getActiveSubscription($userId)?->plan;
    }

    /**
     * Kiểm tra user có thể dùng feature không + trả quota info.
     */
    public function checkQuota(int $userId, string $feature): array
    {
        $plan = $this->getUserPlan($userId);

        if (!$plan) {
            return ['allowed' => false, 'limit' => 0, 'used' => 0, 'plan' => null];
        }

        $limit = $plan->getFeatureLimit($feature);
        $used = SubscriptionUsage::getUsed($userId, $feature);

        $allowed = $limit === -1 || ($limit > 0 && $used < $limit);

        // Boolean features (true/false)
        if (is_bool($plan->features[$feature] ?? null)) {
            $allowed = $plan->features[$feature];
        }

        return [
            'allowed' => $allowed,
            'limit'   => $limit,
            'used'    => $used,
            'plan'    => $plan->slug,
        ];
    }

    /**
     * Consume 1 unit of a feature quota. Return false nếu hết.
     */
    public function useQuota(int $userId, string $feature): bool
    {
        $plan = $this->getUserPlan($userId);
        if (!$plan) return false;

        $limit = $plan->getFeatureLimit($feature);

        // Boolean feature
        if (is_bool($plan->features[$feature] ?? null)) {
            return $plan->features[$feature];
        }

        return SubscriptionUsage::incrementUsage($userId, $feature, $limit);
    }

    /**
     * Subscribe user vào gói Free (không cần PayPal).
     */
    public function subscribeFree(int $userId): UserSubscription
    {
        // Cancel subscription cũ nếu có
        UserSubscription::forUser($userId)->active()->update(['status' => 'cancelled', 'cancelled_at' => now()]);

        $plan = SubscriptionPlan::where('slug', 'free')->firstOrFail();

        return UserSubscription::create([
            'user_id'    => $userId,
            'plan_id'    => $plan->id,
            'status'     => 'active',
            'starts_at'  => now(),
            'ends_at'    => null, // Free không hết hạn
        ]);
    }

    /**
     * Tạo PayPal subscription cho gói paid.
     * Return PayPal approval URL để redirect user.
     */
    public function createPaypalSubscription(int $userId, string $planSlug): ?array
    {
        $plan = SubscriptionPlan::where('slug', $planSlug)->active()->firstOrFail();

        if ($plan->price <= 0) {
            $sub = $this->subscribeFree($userId);
            return ['subscription_id' => $sub->id, 'status' => 'active', 'approval_url' => null];
        }

        if (!$plan->paypal_plan_id) {
            Log::error("PayPal plan_id not set for plan: {$planSlug}");
            return null;
        }

        $token = $this->getPaypalAccessToken();
        if (!$token) return null;

        $baseUrl = config('subscription.paypal.base_url');

        $response = Http::withToken($token)->post("{$baseUrl}/v1/billing/subscriptions", [
            'plan_id'             => $plan->paypal_plan_id,
            'application_context' => [
                'brand_name'          => config('app.name', 'LamGame'),
                'return_url'          => config('subscription.paypal.return_url'),
                'cancel_url'          => config('subscription.paypal.cancel_url'),
                'shipping_preference' => 'NO_SHIPPING',
                'user_action'         => 'SUBSCRIBE_NOW',
            ],
        ]);

        if (!$response->successful()) {
            Log::error('PayPal create subscription failed', ['body' => $response->body()]);
            return null;
        }

        $data = $response->json();
        $approvalUrl = collect($data['links'] ?? [])->firstWhere('rel', 'approve')['href'] ?? null;

        // Tạo pending subscription
        $sub = UserSubscription::create([
            'user_id'                => $userId,
            'plan_id'                => $plan->id,
            'paypal_subscription_id' => $data['id'],
            'status'                 => 'pending',
        ]);

        return [
            'subscription_id' => $sub->id,
            'paypal_id'       => $data['id'],
            'approval_url'    => $approvalUrl,
            'status'          => 'pending',
        ];
    }

    /**
     * Activate subscription sau khi user approve trên PayPal.
     */
    public function activateSubscription(string $paypalSubscriptionId): ?UserSubscription
    {
        $sub = UserSubscription::where('paypal_subscription_id', $paypalSubscriptionId)->first();
        if (!$sub) return null;

        // Cancel subscription cũ của user
        UserSubscription::forUser($sub->user_id)
            ->active()
            ->where('id', '!=', $sub->id)
            ->update(['status' => 'cancelled', 'cancelled_at' => now()]);

        $sub->update([
            'status'    => 'active',
            'starts_at' => now(),
            'ends_at'   => now()->addMonth(),
        ]);

        return $sub;
    }

    /**
     * Verify PayPal webhook signature.
     */
    public function verifyWebhookSignature(Request $request): bool
    {
        $webhookId = config('subscription.paypal.webhook_id');
        if (!$webhookId) {
            Log::error('PAYPAL_WEBHOOK_ID not configured — rejecting webhook');
            return false;
        }

        // Thiếu PayPal headers → không phải request từ PayPal
        if (!$request->header('PAYPAL-TRANSMISSION-ID')) {
            return false;
        }

        try {
            $token = $this->getPaypalAccessToken();
            if (!$token) return false;

            $baseUrl = config('subscription.paypal.base_url');

            $response = Http::withToken($token)->post("{$baseUrl}/v1/notifications/verify-webhook-signature", [
                'auth_algo'         => $request->header('PAYPAL-AUTH-ALGO'),
                'cert_url'          => $request->header('PAYPAL-CERT-URL'),
                'transmission_id'   => $request->header('PAYPAL-TRANSMISSION-ID'),
                'transmission_sig'  => $request->header('PAYPAL-TRANSMISSION-SIG'),
                'transmission_time' => $request->header('PAYPAL-TRANSMISSION-TIME'),
                'webhook_id'        => $webhookId,
                'webhook_event'     => $request->all(),
            ]);

            return $response->successful() && ($response->json('verification_status') === 'SUCCESS');
        } catch (\Exception $e) {
            Log::error('PayPal webhook verify error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Xử lý PayPal webhook event.
     */
    public function handleWebhook(array $event): void
    {
        $type = $event['event_type'] ?? '';
        $resource = $event['resource'] ?? [];

        match ($type) {
            'BILLING.SUBSCRIPTION.ACTIVATED' => $this->activateSubscription($resource['id'] ?? ''),

            'BILLING.SUBSCRIPTION.CANCELLED',
            'BILLING.SUBSCRIPTION.SUSPENDED',
            'BILLING.SUBSCRIPTION.EXPIRED' => $this->cancelSubscription($resource['id'] ?? ''),

            'PAYMENT.SALE.COMPLETED' => $this->recordPayment($resource),

            default => Log::info("Unhandled PayPal webhook: {$type}"),
        };
    }

    /**
     * Cancel subscription.
     */
    public function cancelSubscription(string $paypalSubscriptionId): void
    {
        $sub = UserSubscription::where('paypal_subscription_id', $paypalSubscriptionId)->first();
        if ($sub) {
            $sub->update(['status' => 'cancelled', 'cancelled_at' => now()]);

            SubscriptionTransaction::create([
                'subscription_id' => $sub->id,
                'paypal_transaction_id' => $paypalSubscriptionId,
                'amount' => 0,
                'currency' => 'USD',
                'status' => 'cancelled',
                'paypal_data' => ['event' => 'webhook_cancelled', 'timestamp' => now()->toIso8601String()],
            ]);
        }
    }

    /**
     * Cancel subscription qua PayPal API.
     */
    public function cancelOnPaypal(int $userId): bool
    {
        $sub = $this->getActiveSubscription($userId);
        if (!$sub || !$sub->paypal_subscription_id) return false;

        $token = $this->getPaypalAccessToken();
        if (!$token) return false;

        $baseUrl = config('subscription.paypal.base_url');

        $response = Http::withToken($token)->post(
            "{$baseUrl}/v1/billing/subscriptions/{$sub->paypal_subscription_id}/cancel",
            ['reason' => 'User requested cancellation']
        );

        if ($response->status() === 204 || $response->successful()) {
            $sub->update(['status' => 'cancelled', 'cancelled_at' => now()]);
            return true;
        }

        Log::error('PayPal cancel subscription failed', ['body' => $response->body()]);
        return false;
    }

    private function recordPayment(array $resource): void
    {
        $paypalSubId = $resource['billing_agreement_id'] ?? null;
        if (!$paypalSubId) return;

        $sub = UserSubscription::where('paypal_subscription_id', $paypalSubId)->first();
        if (!$sub) return;

        SubscriptionTransaction::create([
            'subscription_id'      => $sub->id,
            'paypal_transaction_id' => $resource['id'] ?? null,
            'amount'               => $resource['amount']['total'] ?? 0,
            'currency'             => $resource['amount']['currency'] ?? 'USD',
            'status'               => 'completed',
            'paypal_data'          => $resource,
        ]);

        // Gia hạn thêm 1 tháng
        $sub->update(['ends_at' => now()->addMonth()]);
    }

    private function getPaypalAccessToken(): ?string
    {
        $clientId = config('subscription.paypal.client_id');
        $secret = config('subscription.paypal.client_secret');
        $baseUrl = config('subscription.paypal.base_url');

        $response = Http::asForm()
            ->withBasicAuth($clientId, $secret)
            ->post("{$baseUrl}/v1/oauth2/token", ['grant_type' => 'client_credentials']);

        if ($response->successful()) {
            return $response->json('access_token');
        }

        Log::error('PayPal OAuth failed', ['body' => $response->body()]);
        return null;
    }
}
