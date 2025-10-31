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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
            'salary' => 'nullable|string',
            'skills' => 'nullable|string',
            'experience' => 'nullable|integer',
            'company' => 'nullable|string'
        ]);

        $optimized = $this->optimizer->optimizeJobPosting($request->all());

        return response()->json([
            'success' => true,
            'original' => $request->all(),
            'optimized' => $optimized
        ]);
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
