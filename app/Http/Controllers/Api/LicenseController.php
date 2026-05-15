<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LicenseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    public function __construct(private LicenseService $service) {}

    public function productLicenses(int $id): JsonResponse
    {
        return response()->json($this->service->getProductLicenses($id));
    }

    public function verify(Request $request): JsonResponse
    {
        $request->validate(['key' => 'required|string']);
        return response()->json($this->service->verify($request->key));
    }

    public function myLicenses(Request $request): JsonResponse
    {
        return response()->json($this->service->getMyLicenses($request->user()->id));
    }

    public function transfer(Request $request, int $id): JsonResponse
    {
        $request->validate(['new_owner_id' => 'required|integer']);
        $result = $this->service->transfer($id, $request->user()->id, $request->new_owner_id);
        return isset($result['error']) ? response()->json($result, 422) : response()->json($result);
    }
}
