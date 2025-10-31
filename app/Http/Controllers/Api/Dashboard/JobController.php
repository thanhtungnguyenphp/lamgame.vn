<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class JobController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'jobs' => [],
            'total' => 0
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'location' => 'required|string',
            'salary' => 'nullable|numeric'
        ]);

        return response()->json([
            'message' => 'Job created successfully',
            'job' => $request->all()
        ], 201);
    }

    public function show($id): JsonResponse
    {
        return response()->json([
            'job' => ['id' => $id]
        ]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        return response()->json([
            'message' => 'Job updated successfully',
            'job' => array_merge(['id' => $id], $request->all())
        ]);
    }

    public function destroy($id): JsonResponse
    {
        return response()->json([
            'message' => 'Job deleted successfully'
        ]);
    }
}
