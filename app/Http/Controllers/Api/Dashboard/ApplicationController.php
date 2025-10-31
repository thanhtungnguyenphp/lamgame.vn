<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(): JsonResponse
    {
        $user = auth()->user();
        
        return response()->json([
            'applications' => [],
            'total' => 0,
            'user_id' => $user->id,
            'user_name' => $user->name
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'job_id' => 'required|integer',
            'resume' => 'required|string',
            'cover_letter' => 'nullable|string'
        ]);

        $user = auth()->user();
        $applicationData = array_merge($request->all(), [
            'user_id' => $user->id,
            'applicant_name' => $user->name,
            'applicant_email' => $user->email
        ]);

        return response()->json([
            'message' => 'Application submitted successfully',
            'application' => $applicationData
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $user = auth()->user();
        
        return response()->json([
            'application' => [
                'id' => $id,
                'user_id' => $user->id,
                'applicant_name' => $user->name
            ]
        ]);
    }

    public function updateStatus(Request $request, $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,accepted,rejected'
        ]);

        $user = auth()->user();

        return response()->json([
            'message' => 'Application status updated',
            'application' => [
                'id' => $id,
                'status' => $request->status,
                'updated_by' => $user->id
            ]
        ]);
    }
}
