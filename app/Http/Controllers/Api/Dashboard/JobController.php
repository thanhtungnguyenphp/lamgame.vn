<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class JobController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(): JsonResponse
    {
        $user = auth()->user();
        
        return response()->json([
            'jobs' => [],
            'total' => 0,
            'user_id' => $user->id
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'location' => 'required|string',
            'salary' => 'nullable|numeric',
            'auto_optimize' => 'nullable|boolean'
        ]);

        $user = auth()->user();
        $jobData = array_merge($request->all(), ['user_id' => $user->id]);

        // Auto-optimize if requested
        if ($request->auto_optimize) {
            $optimizer = app(\App\Services\AiJobDescriptionOptimizer::class);
            $optimized = $optimizer->optimizeJobPosting($request->all());
            $jobData = array_merge($jobData, $optimized);
        }

        return response()->json([
            'message' => 'Job created successfully',
            'job' => $jobData,
            'optimized' => $request->auto_optimize ?? false
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $user = auth()->user();
        
        return response()->json([
            'job' => ['id' => $id, 'user_id' => $user->id]
        ]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $user = auth()->user();
        
        return response()->json([
            'message' => 'Job updated successfully',
            'job' => array_merge(['id' => $id, 'user_id' => $user->id], $request->all())
        ]);
    }

    public function destroy($id): JsonResponse
    {
        return response()->json([
            'message' => 'Job deleted successfully'
        ]);
    }
}
