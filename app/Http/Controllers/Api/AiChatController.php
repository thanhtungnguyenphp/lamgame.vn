<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AiToolHistory;
use App\Services\AiToolsProxyService;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AiChatController extends Controller
{
    public function __construct(
        private SubscriptionService $subscriptionService,
    ) {}

    /**
     * POST /api/v1/ai-chat/message
     * Proxy chat message to OHHA Core server with auth + quota check.
     */
    public function message(Request $request): JsonResponse
    {
        $request->validate([
            'message'    => 'required|string|min:1|max:5000',
            'session_id' => 'nullable|string|max:36',
            'persona'    => 'nullable|string|in:default,game,secretary,advisor',
        ]);

        $customer = $request->user();
        $quota = $this->subscriptionService->checkQuota($customer->id, 'ai_concept');

        if (!$quota['allowed']) {
            return response()->json([
                'error'   => 'quota_exceeded',
                'message' => 'Bạn đã hết lượt chat AI tháng này. Vui lòng nâng cấp gói.',
                'quota'   => $quota,
            ], 403);
        }

        $ohhaUrl = config('ai-tools.ii_agent.url');
        $timeout = config('ai-tools.ii_agent.timeout', 120);

        try {
            $response = Http::timeout($timeout)
                ->withHeaders(['X-API-Key' => config('ai-tools.ohha_api_key', '')])
                ->post("{$ohhaUrl}/api/chat", [
                    'message'    => $request->input('message'),
                    'session_id' => $request->input('session_id'),
                    'persona'    => $request->input('persona', 'game'),
                ]);

            if (!$response->successful()) {
                return response()->json([
                    'error'   => 'ai_service_unavailable',
                    'message' => 'Dịch vụ AI đang bận. Vui lòng thử lại.',
                ], 502);
            }

            $data = $response->json();

            // Save to history
            AiToolHistory::create([
                'customer_id' => $customer->id,
                'tool_type'   => 'chat',
                'model_used'  => 'ohha-core',
                'prompt'      => $request->input('message'),
                'response'    => $data['response'] ?? '',
                'status'      => 'completed',
            ]);

            // Consume quota
            $this->subscriptionService->useQuota($customer->id, 'ai_concept');

            return response()->json([
                'session_id' => $data['session_id'] ?? null,
                'response'   => $data['response'] ?? '',
                'events'     => $data['events'] ?? [],
            ]);
        } catch (\Exception $e) {
            \Log::error('AI Chat error', ['error' => $e->getMessage()]);
            return response()->json([
                'error'   => 'ai_service_unavailable',
                'message' => 'Dịch vụ AI đang bận. Vui lòng thử lại.',
            ], 502);
        }
    }

    /**
     * POST /api/v1/ai-chat/stream
     * SSE streaming response — proxies OHHA Core streaming endpoint.
     */
    public function stream(Request $request): StreamedResponse
    {
        $request->validate([
            'message'    => 'required|string|min:1|max:5000',
            'session_id' => 'nullable|string|max:36',
            'persona'    => 'nullable|string|in:default,game,secretary,advisor',
        ]);

        $customer = $request->user();
        $quota = $this->subscriptionService->checkQuota($customer->id, 'ai_concept');

        if (!$quota['allowed']) {
            return new StreamedResponse(function () {
                echo "data: " . json_encode(['error' => 'quota_exceeded', 'message' => 'Hết lượt chat AI tháng này.']) . "\n\n";
            }, 200, ['Content-Type' => 'text/event-stream', 'Cache-Control' => 'no-cache']);
        }

        $ohhaUrl = config('ai-tools.ii_agent.url');
        $message = $request->input('message');
        $sessionId = $request->input('session_id');
        $persona = $request->input('persona', 'game');

        return new StreamedResponse(function () use ($ohhaUrl, $message, $sessionId, $persona, $customer) {
            $ch = curl_init("{$ohhaUrl}/api/chat");
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'X-API-Key: ' . config('ai-tools.ohha_api_key', ''),
                    'Accept: text/event-stream',
                ],
                CURLOPT_POSTFIELDS => json_encode([
                    'message'    => $message,
                    'session_id' => $sessionId,
                    'persona'    => $persona,
                    'stream'     => true,
                ]),
                CURLOPT_WRITEFUNCTION => function ($ch, $data) {
                    echo $data;
                    if (ob_get_level()) ob_flush();
                    flush();
                    return strlen($data);
                },
                CURLOPT_TIMEOUT => 120,
            ]);

            curl_exec($ch);
            curl_close($ch);

            // Consume quota after successful stream
            $this->subscriptionService->useQuota($customer->id, 'ai_concept');
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Connection' => 'keep-alive',
        ]);
    }

    /**
     * GET /api/v1/ai-chat/sessions
     * List user's chat sessions.
     */
    public function sessions(Request $request): JsonResponse
    {
        $sessions = AiToolHistory::forCustomer($request->user()->id)
            ->where('tool_type', 'chat')
            ->selectRaw('MAX(id) as id, MIN(created_at) as started_at, COUNT(*) as message_count, MAX(created_at) as last_message_at')
            ->groupBy('customer_id')
            ->latest('last_message_at')
            ->paginate(20);

        return response()->json($sessions);
    }

    /**
     * GET /api/v1/ai-chat/config
     * Return WebSocket connection info for client.
     */
    public function config(Request $request): JsonResponse
    {
        $customer = $request->user();
        $quota = $this->subscriptionService->checkQuota($customer->id, 'ai_concept');

        return response()->json([
            'ws_url'  => config('ai-tools.ii_agent.url') . '/ws',
            'api_key' => config('ai-tools.ohha_api_key', ''),
            'persona' => 'game',
            'quota'   => $quota,
        ]);
    }
}
