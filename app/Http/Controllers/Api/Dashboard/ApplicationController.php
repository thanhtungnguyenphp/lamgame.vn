<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApplicationController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'applications' => [],
            'total' => 0
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'job_id' => 'required|integer',
            'user_id' => 'required|integer',
            'resume' => 'required|string',
            'cover_letter' => 'nullable|string'
        ]);

        return response()->json([
            'message' => 'Application submitted successfully',
            'application' => $request->all()
        ], 201);
    }

    public function show($id): JsonResponse
    {
        return response()->json([
            'application' => ['id' => $id]
        ]);
    }

    public function updateStatus(Request $request, $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,accepted,rejected'
        ]);

        return response()->json([
            'message' => 'Application status updated',
            'application' => ['id' => $id, 'status' => $request->status]
        ]);
    }
}
