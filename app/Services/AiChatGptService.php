<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AiChatGptService
{
    private string $apiKey;
    private string $baseUrl = 'https://api.openai.com/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = config('openai.api_key');
    }

    public function optimizeJobDescription(array $input): array
    {
        $prompt = $this->buildJobOptimizationPrompt($input);
        
        $response = $this->callChatGpt($prompt);
        
        return $this->parseJsonResponse($response);
    }

    private function buildJobOptimizationPrompt(array $input): string
    {
        return "Optimize this job posting data and return ONLY valid JSON format:

Input data: " . json_encode($input) . "

Return optimized job data in this exact JSON structure:
{
  \"title\": \"Professional job title\",
  \"description\": \"Detailed job description (2-3 paragraphs)\",
  \"requirements\": [\"requirement 1\", \"requirement 2\"],
  \"benefits\": [\"benefit 1\", \"benefit 2\"],
  \"skills\": [\"skill 1\", \"skill 2\"],
  \"salary_range\": \"salary range or null\",
  \"location\": \"location or Remote\",
  \"job_type\": \"Full-time/Part-time/Contract\",
  \"experience_level\": \"Entry/Mid/Senior/Executive Level\",
  \"company_culture\": \"Brief company culture description\"
}

Rules:
- Make title professional and clear
- Write engaging description
- Extract relevant skills from input
- Suggest appropriate benefits
- Determine experience level from context
- Return ONLY the JSON, no other text";
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
