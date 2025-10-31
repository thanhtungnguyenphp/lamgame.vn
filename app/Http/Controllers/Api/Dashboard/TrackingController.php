<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TrackingController extends Controller
{
    public function getJobStats(): JsonResponse
    {
        return response()->json([
            'total_jobs' => 0,
            'active_jobs' => 0,
            'total_applications' => 0,
            'pending_applications' => 0
        ]);
    }

    public function getApplicationsByJob($jobId): JsonResponse
    {
        return response()->json([
            'job_id' => $jobId,
            'applications' => [],
            'stats' => [
                'total' => 0,
                'pending' => 0,
                'reviewed' => 0,
                'accepted' => 0,
                'rejected' => 0
            ]
        ]);
    }

    public function getUserApplications($userId): JsonResponse
    {
        return response()->json([
            'user_id' => $userId,
            'applications' => []
        ]);
    }
}
