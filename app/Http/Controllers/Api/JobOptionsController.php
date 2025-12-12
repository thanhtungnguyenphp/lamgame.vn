<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\JobFilterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class JobOptionsController extends Controller
{
    protected JobFilterService $jobFilterService;

    public function __construct(JobFilterService $jobFilterService)
    {
        $this->jobFilterService = $jobFilterService;
    }

    /**
     * Get job posting form data - Main API for job options and attributes
     * 
     * @return JsonResponse
     */
    public function getJobFormData(): JsonResponse
    {
        try {
            $formData = [
                'attributes' => $this->jobFilterService->getJobAttributesForForm(),
                'categories' => $this->jobFilterService->getJobCategories(),
                'popular_skills' => $this->jobFilterService->getSkills(null, null, 100),
                'common_benefits' => $this->jobFilterService->getBenefits(null, 100),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Job form data retrieved successfully',
                'data' => $formData,
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve job form data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Search options (skills, companies, benefits) with autocomplete
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function searchOptions(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:2',
            'types' => 'nullable|array',
            'types.*' => 'string|in:skills,companies,benefits',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        try {
            $query = $request->get('query');
            $types = $request->get('types', ['skills', 'companies']);
            $limit = $request->get('limit', 10);

            $results = $this->jobFilterService->searchAcrossOptions($query, $types, $limit);

            return response()->json([
                'success' => true,
                'message' => 'Search completed successfully',
                'data' => $results,
                'query' => $query,
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}