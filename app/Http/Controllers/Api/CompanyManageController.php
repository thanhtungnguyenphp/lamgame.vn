<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyManageController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $admin = $request->auth_admin;

        $query = Company::where('created_by_admin_id', $admin->id);

        if ($search = $request->input('search')) {
            $search = str_replace(['%', '_'], ['\%', '\_'], $search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('industry', 'like', "%{$search}%");
            });
        }

        $companies = $query->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data'   => CompanyResource::collection($companies),
            'meta'   => [
                'current_page' => $companies->currentPage(),
                'last_page'    => $companies->lastPage(),
                'per_page'     => $companies->perPage(),
                'total'        => $companies->total(),
            ],
        ]);
    }

    public function detail(Request $request, int $id): JsonResponse
    {
        $company = Company::where('id', $id)
            ->where('created_by_admin_id', $request->auth_admin->id)
            ->first();

        if (!$company) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy công ty.'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'   => new CompanyResource($company),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $admin = $request->auth_admin;

        $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'website'        => 'nullable|url',
            'email'          => 'nullable|email',
            'phone'          => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:500',
            'employee_count' => 'nullable|integer|min:1',
            'founded_year'   => 'nullable|integer|min:1900|max:' . date('Y'),
            'industry'       => 'nullable|string|max:100',
            'logo'           => 'nullable|file|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ]);

        $data = $request->except('logo');
        $data['created_by_admin_id'] = $admin->id;
        $data['status'] = true;

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('company-logos', 'public');
        }

        $company = Company::create($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Đã tạo công ty.',
            'data'    => new CompanyResource($company),
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $company = Company::where('id', $id)
            ->where('created_by_admin_id', $request->auth_admin->id)
            ->first();

        if (!$company) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy công ty.'], 404);
        }

        $request->validate([
            'name'           => 'sometimes|string|max:255',
            'description'    => 'nullable|string',
            'website'        => 'nullable|url',
            'email'          => 'nullable|email',
            'phone'          => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:500',
            'employee_count' => 'nullable|integer|min:1',
            'founded_year'   => 'nullable|integer|min:1900|max:' . date('Y'),
            'industry'       => 'nullable|string|max:100',
            'logo'           => 'nullable|file|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ]);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            // Xóa logo cũ
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            $data['logo'] = $request->file('logo')->store('company-logos', 'public');
        }

        $company->update($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Đã cập nhật công ty.',
            'data'    => new CompanyResource($company->fresh()),
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $company = Company::where('id', $id)
            ->where('created_by_admin_id', $request->auth_admin->id)
            ->first();

        if (!$company) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy công ty.'], 404);
        }

        $company->delete();

        return response()->json(['status' => 'success', 'message' => 'Đã xóa công ty.']);
    }
}
