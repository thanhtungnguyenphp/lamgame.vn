<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\HireRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class HireRequestController extends Controller
{
    public function __construct(private HireRequestService $service) {}

    /**
     * POST /api/v1/hire-request
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:100',
            'email'           => 'required|email|max:255',
            'phone'           => 'nullable|string|max:20',
            'company'         => 'nullable|string|max:255',
            'country'         => 'nullable|string|max:100',
            'project_type'    => 'required|in:game,web,app,ai,other',
            'service_package' => 'nullable|in:fixed-scope,dedicated-team,hourly-support,not-sure',
            'source'          => 'nullable|string|max:50',
            'budget_range'    => 'nullable|string|max:50',
            'description'     => 'required|string|max:5000',
        ]);

        // Keep deployment backward-compatible until the nullable attribution migration is applied.
        foreach (['country', 'service_package', 'source'] as $column) {
            if (!Schema::hasColumn('hire_requests', $column)) {
                unset($validated[$column]);
            }
        }

        $hireRequest = $this->service->create($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Yêu cầu báo giá đã được gửi thành công. Chúng tôi sẽ liên hệ bạn sớm nhất!',
            'data'    => $hireRequest,
        ], 201);
    }
}
