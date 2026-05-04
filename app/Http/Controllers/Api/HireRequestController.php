<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\HireRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HireRequestController extends Controller
{
    public function __construct(private HireRequestService $service) {}

    /**
     * POST /api/v1/hire-request
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:100',
            'email'        => 'required|email|max:255',
            'phone'        => 'nullable|string|max:20',
            'company'      => 'nullable|string|max:255',
            'project_type' => 'required|in:game,web,app,ai,other',
            'budget_range' => 'nullable|string|max:50',
            'description'  => 'required|string|max:5000',
        ]);

        $hireRequest = $this->service->create($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Yêu cầu báo giá đã được gửi thành công. Chúng tôi sẽ liên hệ bạn sớm nhất!',
            'data'    => $hireRequest,
        ], 201);
    }
}
