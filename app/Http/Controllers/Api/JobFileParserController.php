<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AiChatGptService;
use App\Services\PromptTemplateService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class JobFileParserController extends Controller
{
    private AiChatGptService $aiService;
    private PromptTemplateService $promptService;

    public function __construct(AiChatGptService $aiService, PromptTemplateService $promptService)
    {
        $this->middleware('auth:sanctum');
        $this->aiService = $aiService;
        $this->promptService = $promptService;
    }

    public function parseJobFile(Request $request): JsonResponse
    {
        $request->validate([
            'job_file' => 'required|file|mimes:txt,pdf,doc,docx|max:2048'
        ]);

        try {
            $jobText = $this->extractTextFromFile($request->file('job_file'));
            
            $parsedData = $this->parseJobDescription($jobText);

            return response()->json([
                'success' => true,
                'original_text' => $jobText,
                'parsed_data' => $parsedData,
                'ai_powered' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'File parsing failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function extractTextFromFile($file): string
    {
        $extension = $file->getClientOriginalExtension();
        
        switch ($extension) {
            case 'txt':
                return file_get_contents($file->getPathname());
            
            case 'pdf':
                // Basic PDF text extraction (requires additional package for full support)
                return "PDF parsing not implemented - please use TXT format";
            
            case 'doc':
            case 'docx':
                // Basic DOC/DOCX extraction (requires additional package for full support)
                return "DOC/DOCX parsing not implemented - please use TXT format";
            
            default:
                throw new \Exception('Unsupported file format');
        }
    }

    private function parseJobDescription(string $jobText): array
    {
        $prompt = $this->promptService->loadTemplate('job_parsing', [
            'job_text' => $jobText
        ]);

        $response = $this->aiService->callChatGptWithPrompt($prompt);
        
        return $this->aiService->parseJsonResponse($response);
    }
}
