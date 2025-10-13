<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JobOptionsResource;
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
     * Get all filter options for job search/create forms
     * 
     * @return JsonResponse
     */
    public function getFilterOptions(): JsonResponse
    {
        try {
            $options = $this->jobFilterService->getAllFilterOptions();

            return response()->json([
                'success' => true,
                'message' => 'Filter options retrieved successfully',
                'data' => $options,
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve filter options',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get locations for job posting
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getLocations(Request $request): JsonResponse
    {
        try {
            $search = $request->get('search');
            $limit = min($request->get('limit', 50), 100);
            
            $locations = $this->jobFilterService->getLocations($search, $limit);

            return response()->json([
                'success' => true,
                'message' => 'Locations retrieved successfully',
                'data' => $locations,
                'total' => count($locations),
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve locations',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get skills for job posting
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getSkills(Request $request): JsonResponse
    {
        try {
            $search = $request->get('search');
            $category = $request->get('category'); // IT, Marketing, etc.
            $limit = min($request->get('limit', 50), 100);
            
            $skills = $this->jobFilterService->getSkills($search, $category, $limit);

            return response()->json([
                'success' => true,
                'message' => 'Skills retrieved successfully',
                'data' => $skills,
                'total' => count($skills),
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve skills',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get companies that have posted jobs
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getCompanies(Request $request): JsonResponse
    {
        try {
            $search = $request->get('search');
            $limit = min($request->get('limit', 50), 100);
            
            $companies = $this->jobFilterService->getCompanies($search, $limit);

            return response()->json([
                'success' => true,
                'message' => 'Companies retrieved successfully',
                'data' => $companies,
                'total' => count($companies),
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve companies',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get job benefits options
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getBenefits(Request $request): JsonResponse
    {
        try {
            $search = $request->get('search');
            $limit = min($request->get('limit', 50), 100);
            
            $benefits = $this->jobFilterService->getBenefits($search, $limit);

            return response()->json([
                'success' => true,
                'message' => 'Benefits retrieved successfully',
                'data' => $benefits,
                'total' => count($benefits),
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve benefits',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get salary ranges with statistics
     * 
     * @return JsonResponse
     */
    public function getSalaryRanges(): JsonResponse
    {
        try {
            $salaryRanges = $this->jobFilterService->getSalaryRangesWithStats();

            return response()->json([
                'success' => true,
                'message' => 'Salary ranges retrieved successfully',
                'data' => $salaryRanges,
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve salary ranges',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get job industries/categories with job counts
     * 
     * @return JsonResponse
     */
    public function getIndustries(): JsonResponse
    {
        try {
            $industries = $this->jobFilterService->getIndustriesWithJobCounts();

            return response()->json([
                'success' => true,
                'message' => 'Industries retrieved successfully',
                'data' => $industries,
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve industries',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get popular job searches/keywords
     * 
     * @return JsonResponse
     */
    public function getPopularKeywords(): JsonResponse
    {
        try {
            $keywords = $this->jobFilterService->getPopularKeywords();

            return response()->json([
                'success' => true,
                'message' => 'Popular keywords retrieved successfully',
                'data' => $keywords,
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve popular keywords',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get job posting form template data
     * This combines multiple APIs into one for form initialization
     * 
     * @return JsonResponse
     */
    public function getJobFormData(): JsonResponse
    {
        try {
            $formData = [
                'attributes' => $this->jobFilterService->getJobAttributesForForm(),
                'categories' => $this->jobFilterService->getJobCategories(),
                'locations' => $this->jobFilterService->getLocations(null, 20),
                'popular_skills' => $this->jobFilterService->getSkills(null, null, 30),
                'common_benefits' => $this->jobFilterService->getBenefits(null, 20),
                'application_methods' => $this->jobFilterService->getApplicationMethods(),
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
     * Search across multiple option types
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function searchOptions(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:2',
            'types' => 'nullable|array',
            'types.*' => 'string|in:skills,locations,companies,benefits',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        try {
            $query = $request->get('query');
            $types = $request->get('types', ['skills', 'locations', 'companies']);
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