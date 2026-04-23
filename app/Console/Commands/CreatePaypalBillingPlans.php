<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CreatePaypalBillingPlans extends Command
{
    protected $signature = 'paypal:create-plans';
    protected $description = 'Create PayPal live billing plans for Pro and Business subscriptions';

    public function handle(): int
    {
        $baseUrl = config('subscription.paypal.base_url');
        $token = $this->getAccessToken($baseUrl);

        if (!$token) {
            $this->error('Failed to get PayPal access token. Check credentials.');
            return 1;
        }

        $this->info("PayPal API: {$baseUrl}");

        $plans = DB::table('subscription_plans')
            ->whereIn('slug', ['pro', 'business'])
            ->get();

        foreach ($plans as $plan) {
            $this->info("Creating plan for: {$plan->name} (\${$plan->price}/{$plan->billing_interval})");

            // Step 1: Create Product
            $product = Http::withToken($token)
                ->post("{$baseUrl}/v1/catalogs/products", [
                    'name' => "LamGame {$plan->name}",
                    'type' => 'SERVICE',
                    'description' => "LamGame {$plan->name} subscription",
                ]);

            if (!$product->successful()) {
                $this->error("Failed to create product: {$product->body()}");
                continue;
            }

            $productId = $product->json('id');
            $this->info("  Product created: {$productId}");

            // Step 2: Create Billing Plan
            $billingPlan = Http::withToken($token)
                ->post("{$baseUrl}/v1/billing/plans", [
                    'product_id' => $productId,
                    'name' => "LamGame {$plan->name} - Monthly",
                    'billing_cycles' => [
                        [
                            'frequency' => [
                                'interval_unit' => 'MONTH',
                                'interval_count' => 1,
                            ],
                            'tenure_type' => 'REGULAR',
                            'sequence' => 1,
                            'total_cycles' => 0, // infinite
                            'pricing_scheme' => [
                                'fixed_price' => [
                                    'value' => number_format($plan->price, 2, '.', ''),
                                    'currency_code' => $plan->currency,
                                ],
                            ],
                        ],
                    ],
                    'payment_preferences' => [
                        'auto_bill_outstanding' => true,
                        'payment_failure_threshold' => 3,
                    ],
                ]);

            if (!$billingPlan->successful()) {
                $this->error("Failed to create billing plan: {$billingPlan->body()}");
                continue;
            }

            $planId = $billingPlan->json('id');
            $this->info("  Billing plan created: {$planId}");

            // Step 3: Update DB
            DB::table('subscription_plans')
                ->where('slug', $plan->slug)
                ->update(['paypal_plan_id' => $planId]);

            $this->info("  DB updated: subscription_plans.paypal_plan_id = {$planId}");
        }

        $this->info('Done!');
        return 0;
    }

    private function getAccessToken(string $baseUrl): ?string
    {
        $response = Http::asForm()
            ->withBasicAuth(
                config('subscription.paypal.client_id'),
                config('subscription.paypal.client_secret')
            )
            ->post("{$baseUrl}/v1/oauth2/token", ['grant_type' => 'client_credentials']);

        return $response->successful() ? $response->json('access_token') : null;
    }
}
