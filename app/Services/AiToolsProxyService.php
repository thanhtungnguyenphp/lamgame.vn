<?php

namespace App\Services;

use App\Models\AiToolHistory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiToolsProxyService
{
    private SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Execute AI tool request: check quota → call II-Agent → save history → use quota.
     */
    public function execute(int $customerId, string $toolType, string $prompt, array $options = []): array
    {
        $quotaFeature = config("ai-tools.quota_map.{$toolType}");
        if (!$quotaFeature) {
            return ['error' => 'invalid_tool', 'message' => 'Tool type không hợp lệ'];
        }

        // Check quota
        $quota = $this->subscriptionService->checkQuota($customerId, $quotaFeature);
        if (!$quota['allowed']) {
            return [
                'error'   => $quota['limit'] === 0 ? 'feature_not_available' : 'quota_exceeded',
                'message' => $quota['limit'] === 0
                    ? 'Tính năng này không có trong gói của bạn'
                    : "Bạn đã hết lượt {$quotaFeature} tháng này",
                'quota'   => $quota,
                'upsell'  => $this->getUpsell($quota['plan'], $toolType),
            ];
        }

        // Resolve model & build prompt
        $model = $this->resolveModel($customerId, $toolType);
        $systemPrompt = $this->buildSystemPrompt($toolType, $options);
        $maxTokens = config("ai-tools.max_tokens.{$toolType}", 4096);

        // Create history record
        $history = AiToolHistory::create([
            'customer_id' => $customerId,
            'tool_type'   => $toolType,
            'model_used'  => $model,
            'prompt'      => $prompt,
            'metadata'    => $options ?: null,
            'status'      => 'pending',
        ]);

        // Call LLM — supports OpenAI, DeepSeek, Gemini, Anthropic
        $startTime = microtime(true);
        try {
            [$text, $tokensIn, $tokensOut, $durationMs, $usedModel] = $this->callLLMWithFallback(
                $model,
                $systemPrompt,
                $prompt,
                $maxTokens,
                $startTime,
            );

            $history->update([
                'status'        => 'completed',
                'model_used'    => $usedModel,
                'response'      => $text,
                'tokens_input'  => $tokensIn,
                'tokens_output' => $tokensOut,
                'cost_usd'      => $this->estimateCost($usedModel, $tokensIn, $tokensOut),
                'duration_ms'   => $durationMs,
            ]);

            // Use quota after success
            $this->subscriptionService->useQuota($customerId, $quotaFeature);
            $quotaAfter = $this->subscriptionService->checkQuota($customerId, $quotaFeature);

            return [
                'id'              => $history->id,
                'tool_type'       => $toolType,
                'status'          => 'completed',
                'response'        => $text,
                'model_used'      => $usedModel,
                'tokens_input'    => $tokensIn,
                'tokens_output'   => $tokensOut,
                'duration_ms'     => $durationMs,
                'quota_remaining' => max(0, ($quotaAfter['limit'] === -1 ? PHP_INT_MAX : $quotaAfter['limit']) - $quotaAfter['used']),
            ];
        } catch (\Exception $e) {
            $durationMs = (int) ((microtime(true) - $startTime) * 1000);
            Log::error('AI Tools error', ['tool' => $toolType, 'customer' => $customerId, 'error' => $e->getMessage()]);
            $history->update(['status' => 'failed', 'error_message' => $e->getMessage(), 'duration_ms' => $durationMs]);
            return ['error' => 'ai_service_unavailable', 'message' => 'Dịch vụ AI đang bận. Vui lòng thử lại'];
        }
    }

    public function resolveModel(int $customerId, string $toolType): string
    {
        $plan = $this->subscriptionService->getUserPlan($customerId);
        $slug = $plan?->slug ?? 'free';
        $models = config('ai-tools.models', []);
        $aliases = config('ai-tools.plan_aliases', []);
        $modelConfig = $models[$slug] ?? $models[$aliases[$slug] ?? ''] ?? null;

        if ($modelConfig === null) {
            Log::warning('Unknown AI subscription plan; using free model', ['plan' => $slug]);
            $modelConfig = $models['free'] ?? 'gemini-2.5-flash';
        }

        if (is_array($modelConfig)) {
            $codeTasks = ['codegen', 'debug', 'review', 'test'];
            return in_array($toolType, $codeTasks, true)
                ? ($modelConfig['code'] ?? $modelConfig['default'])
                : $modelConfig['default'];
        }

        return $modelConfig;
    }

    public function buildSystemPrompt(string $toolType, array $options = []): string
    {
        $engine = $options['engine'] ?? 'any';
        $language = $options['language'] ?? 'any';

        return match ($toolType) {
            'concept' => 'You are an expert game designer. Given a game idea, create a structured Game Design Document mini with: 1) Game title suggestions (3-5), 2) Gameplay description (200-500 words), 3) Core mechanics, 4) Target platform, 5) Tech stack suggestion, 6) Monetization strategy, 7) Development timeline. Focus on practical designs for indie developers. Output in the user\'s language.',
            'codegen' => "You are an expert {$engine} game developer using {$language}. Generate clean, production-ready code. Include: complete copy-paste ready code, inline comments, {$engine} best practices, integration instructions, and required dependencies.",
            'debug'   => 'You are an expert game developer and debugger. Analyze the code and error. Provide: 1) Root cause analysis, 2) Fixed code (changed parts only), 3) Why the bug occurred, 4) Prevention tips. Be concise.',
            'test'    => "You are an expert in testing {$engine} games using {$language}. Generate a comprehensive test suite: happy path, edge cases, error cases, mocks/stubs. Each test should have a descriptive name.",
            'review'  => 'You are a senior game developer performing code review. Output: Score (1-10), Issues by severity (CRITICAL/WARNING/INFO) with line numbers and fixes, Performance concerns, Best practices violations, Refactored code if needed.',
            'asset'   => 'You are generating a game asset based on the user\'s description.',
            'generate_image' => 'You are a game asset artist. Generate a detailed description for creating: ' . ($options['style'] ?? 'pixel-art') . ' style game asset (' . ($options['type'] ?? 'sprite') . '). Size: ' . ($options['size'] ?? '64x64') . '. Be very specific about colors, shapes, animation frames if applicable. Output a clear prompt suitable for image generation.',
            'gdd_generator' => 'You are an expert game designer creating a full Game Design Document. Genre: ' . ($options['genre'] ?? 'any') . '. Platform: ' . ($options['platform'] ?? 'mobile') . '. Monetization: ' . ($options['monetization'] ?? 'f2p') . '. Create a comprehensive GDD with sections: 1) Executive Summary, 2) Gameplay Mechanics, 3) Game World & Story, 4) Art & Audio Direction, 5) UI/UX Flow, 6) Monetization Design, 7) Technical Requirements, 8) Development Milestones, 9) KPIs & Success Metrics. Output in the user\'s language.',
            default   => 'You are a helpful AI assistant for game developers.',
        };
    }

    private function getUpsell(?string $currentPlan, string $toolType): array
    {
        $target = match ($currentPlan) {
            null, 'free' => 'basic',
            'basic'      => 'pro',
            'pro'        => 'studio',
            'business'   => 'studio',
            'studio'     => 'enterprise',
            default      => 'pro',
        };
        return ['plan' => $target, 'url' => '/ai-tools'];
    }

    /**
     * Retry the primary model and then fail over to configured providers.
     * Prompt content is never written to retry logs.
     */
    private function callLLMWithFallback(
        string $primaryModel,
        string $systemPrompt,
        string $prompt,
        int $maxTokens,
        float $startTime,
    ): array {
        $models = array_values(array_unique(array_filter([
            $primaryModel,
            ...config('ai-tools.fallback_models', []),
        ])));
        $attempts = max(1, (int) config('ai-tools.ii_agent.attempts', 2));
        $delayMs = max(0, (int) config('ai-tools.ii_agent.retry_delay_ms', 300));
        $lastException = null;

        foreach ($models as $model) {
            for ($attempt = 1; $attempt <= $attempts; $attempt++) {
                try {
                    $result = $this->callLLM($model, $systemPrompt, $prompt, $maxTokens, $startTime);
                    return [...$result, $model];
                } catch (\Throwable $exception) {
                    $lastException = $exception;
                    Log::warning('AI provider attempt failed', [
                        'model' => $model,
                        'attempt' => $attempt,
                        'status' => $exception->getCode(),
                    ]);
                    if ($attempt < $attempts && $delayMs > 0) {
                        usleep($delayMs * 1000);
                    }
                }
            }
        }

        throw new \RuntimeException('All configured AI providers failed', 0, $lastException);
    }

    /**
     * Call LLM API — auto-detect provider from model name.
     * Returns [text, tokensIn, tokensOut, durationMs]
     */
    private function callLLM(string $model, string $systemPrompt, string $prompt, int $maxTokens, float $startTime): array
    {
        $timeout = config('ai-tools.ii_agent.timeout');

        // DeepSeek (OpenAI-compatible API)
        if (str_starts_with($model, 'deepseek')) {
            $response = Http::timeout($timeout)
                ->withToken(config('ai-tools.deepseek_key'))
                ->post('https://api.deepseek.com/chat/completions', [
                    'model'      => $model,
                    'max_tokens' => $maxTokens,
                    'messages'   => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                ]);
            $this->throwIfFailed($response);
            $data = $response->json();
            return [
                $data['choices'][0]['message']['content'] ?? '',
                $data['usage']['prompt_tokens'] ?? 0,
                $data['usage']['completion_tokens'] ?? 0,
                (int) ((microtime(true) - $startTime) * 1000),
            ];
        }

        // Gemini
        if (str_starts_with($model, 'gemini')) {
            $geminiModel = $model ?: 'gemini-2.0-flash';
            $response = Http::timeout($timeout)
                ->post("https://generativelanguage.googleapis.com/v1beta/models/{$geminiModel}:generateContent?key=" . config('ai-tools.gemini_key'), [
                    'systemInstruction' => ['parts' => [['text' => $systemPrompt]]],
                    'contents'          => [['parts' => [['text' => $prompt]]]],
                    'generationConfig'  => ['maxOutputTokens' => $maxTokens],
                ]);
            $this->throwIfFailed($response);
            $data = $response->json();
            return [
                $data['candidates'][0]['content']['parts'][0]['text'] ?? '',
                $data['usageMetadata']['promptTokenCount'] ?? 0,
                $data['usageMetadata']['candidatesTokenCount'] ?? 0,
                (int) ((microtime(true) - $startTime) * 1000),
            ];
        }

        // Claude / Anthropic
        if (str_starts_with($model, 'claude')) {
            $response = Http::timeout($timeout)
                ->withHeaders(['x-api-key' => config('ai-tools.anthropic_key'), 'anthropic-version' => '2023-06-01'])
                ->post('https://api.anthropic.com/v1/messages', [
                    'model'      => $model,
                    'max_tokens' => $maxTokens,
                    'system'     => $systemPrompt,
                    'messages'   => [['role' => 'user', 'content' => $prompt]],
                ]);
            $this->throwIfFailed($response);
            $data = $response->json();
            return [
                $data['content'][0]['text'] ?? '',
                $data['usage']['input_tokens'] ?? 0,
                $data['usage']['output_tokens'] ?? 0,
                (int) ((microtime(true) - $startTime) * 1000),
            ];
        }

        // OpenAI (default: gpt-4o-mini, gpt-4o, etc.)
        $response = Http::timeout($timeout)
            ->withToken(config('ai-tools.openai_key'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model'      => $model,
                'max_tokens' => $maxTokens,
                'messages'   => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);
        $this->throwIfFailed($response);
        $data = $response->json();
        return [
            $data['choices'][0]['message']['content'] ?? '',
            $data['usage']['prompt_tokens'] ?? 0,
            $data['usage']['completion_tokens'] ?? 0,
            (int) ((microtime(true) - $startTime) * 1000),
        ];
    }

    private function throwIfFailed($response): void
    {
        if (!$response->successful()) {
            throw new \RuntimeException('LLM API error: ' . $response->status() . ' ' . $response->body());
        }
    }

    private function estimateCost(string $model, int $tokensIn, int $tokensOut): float
    {
        $rates = [
            'deepseek-chat'     => ['in' => 0.14, 'out' => 0.28],
            'gpt-4o-mini'       => ['in' => 0.15, 'out' => 0.60],
            'gpt-4o'            => ['in' => 2.50, 'out' => 10.00],
            'gemini-2.0-flash'  => ['in' => 0.10, 'out' => 0.40],
            'gemini-2.5-flash'  => ['in' => 0.30, 'out' => 2.50],
            'claude-sonnet-4-6' => ['in' => 3.00, 'out' => 15.00],
        ];
        $rate = $rates[$model] ?? $rates['deepseek-chat'];
        return ($tokensIn * $rate['in'] + $tokensOut * $rate['out']) / 1_000_000;
    }
}
