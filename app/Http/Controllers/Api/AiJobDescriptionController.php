<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AiJobDescriptionOptimizer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AiJobDescriptionController extends Controller
{
    private AiJobDescriptionOptimizer $optimizer;

    public function __construct(AiJobDescriptionOptimizer $optimizer)
    {
        $this->middleware('auth:sanctum');
        $this->optimizer = $optimizer;
    }

    public function optimize(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255', 
            'company_name' => 'nullable|string',
            'company' => 'nullable|string',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
            'salary' => 'nullable|string',
            'salary_range' => 'nullable|string',
            'skills' => 'nullable|string',
            'experience' => 'nullable|integer',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'industry' => 'nullable|string',
            'work_mode' => 'nullable|string',
            'job_type' => 'nullable|string',
            'open_positions' => 'nullable|string',
            'nice_to_have' => 'nullable|string',
            'keywords' => 'nullable|string'
        ]);

        // Normalize the data to standard field names
        $normalizedData = [
            'title' => $request->input('title') ?: $request->input('job_title'),
            'company' => $request->input('company') ?: $request->input('company_name'),
            'description' => $request->input('description') ?: $request->input('company_description'),
            'location' => $request->input('location'),
            'salary' => $request->input('salary') ?: $request->input('salary_range'),
            'skills' => $request->input('skills') ?: $request->input('keywords'),
            'requirements' => $request->input('requirements'),
            'benefits' => $request->input('benefits'),
            'responsibilities' => $request->input('responsibilities'),
            'industry' => $request->input('industry'),
            'work_mode' => $request->input('work_mode'),
            'job_type' => $request->input('job_type'),
            'nice_to_have' => $request->input('nice_to_have')
        ];

        // Remove null values
        $normalizedData = array_filter($normalizedData, function($value) {
            return $value !== null;
        });

        try {
            $optimized = $this->optimizer->optimizeJobPosting($normalizedData);

            return response()->json([
                'success' => true,
                'original' => $request->all(),
                'normalized' => $normalizedData,
                'optimized' => $optimized,
                'ai_powered' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'AI optimization failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function generateSuggestions(Request $request): JsonResponse
    {
        $request->validate([
            'job_title' => 'required|string'
        ]);

        $suggestions = [
            'skills' => $this->getSuggestedSkills($request->job_title),
            'benefits' => $this->getSuggestedBenefits(),
            'requirements' => $this->getSuggestedRequirements($request->job_title)
        ];

        return response()->json([
            'success' => true,
            'suggestions' => $suggestions
        ]);
    }

    private function getSuggestedSkills(string $jobTitle): array
    {
        $skillMap = [
            'developer' => ['PHP', 'Laravel', 'JavaScript', 'MySQL', 'Git'],
            'designer' => ['Photoshop', 'Figma', 'UI/UX', 'Adobe Creative Suite'],
            'manager' => ['Leadership', 'Project Management', 'Communication', 'Strategic Planning'],
            'marketing' => ['Digital Marketing', 'SEO', 'Social Media', 'Analytics']
        ];

        foreach ($skillMap as $role => $skills) {
            if (stripos($jobTitle, $role) !== false) {
                return $skills;
            }
        }

        return ['Communication', 'Teamwork', 'Problem Solving'];
    }

    private function getSuggestedBenefits(): array
    {
        return [
            'Health Insurance',
            'Flexible Working Hours',
            'Remote Work Options',
            'Professional Development',
            'Competitive Salary'
        ];
    }

    private function getSuggestedRequirements(string $jobTitle): array
    {
        return [
            'Bachelor\'s degree or equivalent experience',
            'Strong communication skills',
            'Ability to work in a team environment'
        ];
    }
}
