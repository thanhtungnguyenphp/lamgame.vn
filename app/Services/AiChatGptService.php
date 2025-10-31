<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AiChatGptService
{
    private string $apiKey;
    private string $baseUrl = 'https://api.openai.com/v1/chat/completions';

    private PromptTemplateService $promptService;

    public function __construct(PromptTemplateService $promptService)
    {
        $this->apiKey = config('openai.api_key');
        $this->promptService = $promptService;
    }

    public function optimizeJobDescription(array $input): array
    {
        $prompt = $this->promptService->getJobCreationPrompt($input);
        
        $response = $this->callChatGpt($prompt);
        
        return $this->parseJsonResponse($response);
    }

    private function callChatGpt(string $prompt): string
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(30)->post($this->baseUrl, [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a professional HR assistant that optimizes job postings. Always return valid JSON format.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'max_tokens' => 1000,
            'temperature' => 0.7
        ]);

        if ($response->failed()) {
            throw new \Exception('ChatGPT API call failed: ' . $response->body());
        }

        return $response->json('choices.0.message.content');
    }

    private function parseJsonResponse(string $response): array
    {
        // Clean response to extract JSON
        $response = trim($response);
        $response = preg_replace('/^```json\s*/', '', $response);
        $response = preg_replace('/\s*```$/', '', $response);
        
        $decoded = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON response from ChatGPT: ' . json_last_error_msg());
        }
        
        return $decoded;
    }
}
