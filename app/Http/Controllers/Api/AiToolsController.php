<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AiToolHistory;
use App\Services\AiToolsProxyService;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiToolsController extends Controller
{
    public function __construct(
        private AiToolsProxyService $proxyService,
        private SubscriptionService $subscriptionService,
    ) {}

    /**
     * GET /api/v1/ai-tools/dashboard
     */
    public function dashboard(Request $request): JsonResponse
    {
        $customer = $request->user();
        $sub = $this->subscriptionService->getActiveSubscription($customer->id);
        $plan = $sub?->plan;

        $quotaFeatures = config('ai-tools.quota_map');
        $quota = [];
        foreach ($quotaFeatures as $tool => $feature) {
            $q = $this->subscriptionService->checkQuota($customer->id, $feature);
            $quota[$feature] = [
                'limit'     => $q['limit'],
                'used'      => $q['used'],
                'remaining' => $q['limit'] === -1 ? -1 : max(0, $q['limit'] - $q['used']),
            ];
        }

        $recentHistory = AiToolHistory::forCustomer($customer->id)
            ->where('status', 'completed')
            ->latest()
            ->take(10)
            ->get(['id', 'tool_type', 'prompt', 'status', 'created_at']);

        return response()->json([
            'plan' => $plan ? [
                'name'    => $plan->name,
                'slug'    => $plan->slug,
                'price'   => $plan->price,
                'ends_at' => $sub->ends_at,
            ] : null,
            'quota'          => $quota,
            'recent_history' => $recentHistory,
        ]);
    }

    /**
     * POST /api/v1/ai-tools/concept
     */
    public function concept(Request $request): JsonResponse
    {
        $request->validate([
            'prompt' => 'required|string|min:10|max:2000',
            'options.platform' => 'nullable|string|in:mobile,web,pc,console',
            'options.genre'    => 'nullable|string',
        ]);

        return $this->executeToolRequest($request, 'concept');
    }

    /**
     * POST /api/v1/ai-tools/codegen
     */
    public function codegen(Request $request): JsonResponse
    {
        $request->validate([
            'prompt'           => 'required|string|min:10|max:3000',
            'options.engine'   => 'required|string|in:unity,godot,phaser,cocos,pygame',
            'options.language' => 'required|string|in:csharp,gdscript,javascript,typescript,python',
        ]);

        return $this->executeToolRequest($request, 'codegen');
    }

    /**
     * POST /api/v1/ai-tools/debug
     */
    public function debug(Request $request): JsonResponse
    {
        $request->validate([
            'prompt'    => 'required|string|min:10|max:2000',
            'code'      => 'required|string|max:10000',
            'error_log' => 'nullable|string|max:5000',
        ]);

        $prompt = $request->input('prompt')
            . "\n\n--- CODE ---\n" . $request->input('code');
        if ($request->input('error_log')) {
            $prompt .= "\n\n--- ERROR LOG ---\n" . $request->input('error_log');
        }

        $request->merge(['prompt' => $prompt]);
        return $this->executeToolRequest($request, 'debug');
    }

    /**
     * POST /api/v1/ai-tools/test
     */
    public function test(Request $request): JsonResponse
    {
        $request->validate([
            'code'             => 'required|string|max:10000',
            'options.engine'   => 'required|string|in:unity,godot,phaser,cocos,pygame',
            'options.language' => 'required|string|in:csharp,gdscript,javascript,typescript,python',
        ]);

        $request->merge(['prompt' => "Generate unit tests for:\n\n" . $request->input('code')]);
        return $this->executeToolRequest($request, 'test');
    }

    /**
     * POST /api/v1/ai-tools/review
     */
    public function review(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|max:15000',
        ]);

        $request->merge(['prompt' => "Review this code:\n\n" . $request->input('code')]);
        return $this->executeToolRequest($request, 'review');
    }

    /**
     * GET /api/v1/ai-tools/history
     */
    public function history(Request $request): JsonResponse
    {
        $customer = $request->user();

        $query = AiToolHistory::forCustomer($customer->id)->latest();

        if ($request->has('tool_type')) {
            $query->ofType($request->input('tool_type'));
        }

        $paginated = $query->paginate($request->input('per_page', 20));

        return response()->json($paginated);
    }

    /**
     * GET /api/v1/ai-tools/history/{id}
     */
    public function historyDetail(Request $request, int $id): JsonResponse
    {
        $history = AiToolHistory::forCustomer($request->user()->id)->findOrFail($id);
        return response()->json($history);
    }

    private function executeToolRequest(Request $request, string $toolType): JsonResponse
    {
        $customer = $request->user();
        $result = $this->proxyService->execute(
            $customer->id,
            $toolType,
            $request->input('prompt'),
            $request->input('options', []),
        );

        if (isset($result['error'])) {
            $statusCode = match ($result['error']) {
                'quota_exceeded', 'feature_not_available' => 403,
                'ai_service_unavailable' => 502,
                default => 400,
            };
            return response()->json($result, $statusCode);
        }

        return response()->json($result);
    }
}
