<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Company;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $jobs = $this->getUserJobs($request->get('page', 1));
        $stats = $this->getJobStats();
        
        return view('admin.jobs.index', compact('jobs', 'stats'));
    }

    public function create()
    {
        $admin = Auth::guard('admin')->user();
        $company = null;
        
        if ($admin && $admin->company_id) {
            $company = Company::find($admin->company_id);
        }
        
        return view('admin.jobs.create', compact('company'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $admin = Auth::guard('admin')->user();
            $companyId = null;
            
            if ($request->has('company')) {
                $companyData = $request->company;
                
                if ($request->hasFile('company_logo')) {
                    $logoPath = $this->uploadCompanyLogo($request->file('company_logo'));
                    if ($logoPath) {
                        $companyData['logo'] = $logoPath;
                    }
                }
                
                if ($admin->company_id) {
                    $company = Company::find($admin->company_id);
                    if ($company) {
                        $company->update($companyData);
                        $companyId = $company->id;
                    }
                } else {
                    $companyData['created_by_admin_id'] = $admin->id;
                    $company = Company::create($companyData);
                    $companyId = $company->id;
                    $admin->update(['company_id' => $company->id]);
                }
            } else if ($admin->company_id) {
                $companyId = $admin->company_id;
            }
            
            $sku = 'JOB_' . strtoupper(uniqid());
            
            $productData = [
                'type' => 'job',
                'sku' => $sku,
                'attribute_family_id' => 1,
                'created_by_admin_id' => $admin->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            if ($companyId) {
                $productData['company_id'] = $companyId;
            }
            
            $productId = DB::table('products')->insertGetId($productData);
            
            DB::table('product_flat')->insert([
                'product_id' => $productId,
                'sku' => $sku,
                'type' => 'job',
                'name' => $request->title,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'status' => 1,
                'visible_individually' => 1,
                'url_key' => strtolower(str_replace(' ', '-', $request->title)) . '-' . $productId,
                'meta_title' => $request->title,
                'meta_description' => $request->short_description,
                'locale' => 'vi',
                'channel' => 'default',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $this->saveJobAttributes($productId, $request);
            
            DB::commit();
            return redirect()->route('admin.jobs.index')->with('success', 'Job đã được tạo thành công!');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Lỗi: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $admin = Auth::guard('admin')->user();
        
        $job = DB::table('products')
            ->leftJoin('product_flat', 'products.id', '=', 'product_flat.product_id')
            ->select('products.*', 'product_flat.name', 'product_flat.description', 'product_flat.short_description')
            ->where('products.id', $id)
            ->where('products.created_by_admin_id', $admin->id)
            ->first();
            
        if (!$job) {
            return redirect()->route('admin.jobs.index')->withErrors(['error' => 'Job không tồn tại']);
        }

        $attributes = DB::table('product_attribute_values')
            ->where('product_id', $id)
            ->whereIn('attribute_id', [40, 41, 42, 43, 45, 48, 50, 51])
            ->pluck('text_value', 'attribute_id');

        $company = null;
        if ($admin && $admin->company_id) {
            $company = Company::find($admin->company_id);
            
            if ($company && $company->logo) {
                $path = 'company-logos/' . basename($company->logo);
                if (\Storage::disk('public')->exists($path)) {
                    try {
                        $file = \Storage::disk('public')->get($path);
                        $mimeType = \Storage::disk('public')->mimeType($path);
                        $company->logo_base64 = 'data:' . $mimeType . ';base64,' . base64_encode($file);
                    } catch (\Exception $e) {
                        \Log::error('Failed to encode logo: ' . $e->getMessage());
                    }
                }
            }
        }
        
        return view('admin.jobs.edit', compact('job', 'attributes', 'company'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $admin = Auth::guard('admin')->user();
            $companyId = null;
            
            if ($request->has('company')) {
                $companyData = $request->company;
                
                if ($request->hasFile('company_logo')) {
                    $logoPath = $this->uploadCompanyLogo($request->file('company_logo'));
                    if ($logoPath) {
                        $companyData['logo'] = $logoPath;
                    }
                }
                
                if ($admin->company_id) {
                    $company = Company::find($admin->company_id);
                    if ($company) {
                        $company->update($companyData);
                        $companyId = $company->id;
                    }
                } else {
                    $companyData['created_by_admin_id'] = $admin->id;
                    $company = Company::create($companyData);
                    $companyId = $company->id;
                    $admin->update(['company_id' => $company->id]);
                }
            } else if ($admin->company_id) {
                $companyId = $admin->company_id;
            }
            
            DB::table('product_flat')
                ->where('product_id', $id)
                ->update([
                    'name' => $request->title,
                    'description' => $request->description,
                    'short_description' => $request->short_description,
                    'updated_at' => now()
                ]);
            
            $productUpdateData = ['updated_at' => now()];
            if ($companyId) {
                $productUpdateData['company_id'] = $companyId;
            }
            
            DB::table('products')
                ->where('id', $id)
                ->where('created_by_admin_id', $admin->id)
                ->update($productUpdateData);

            DB::table('product_attribute_values')->where('product_id', $id)->delete();
            $this->saveJobAttributes($id, $request);
            
            DB::commit();
            return redirect()->route('admin.jobs.index')->with('success', 'Job đã được cập nhật!');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Lỗi: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            DB::table('product_attribute_values')->where('product_id', $id)->delete();
            DB::table('product_flat')->where('product_id', $id)->delete();
            
            $deleted = DB::table('products')
                ->where('id', $id)
                ->where('created_by_admin_id', Auth::guard('admin')->id())
                ->delete();
                
            if (!$deleted) {
                throw new \Exception('Job không tồn tại hoặc bạn không có quyền xóa');
            }
            
            DB::commit();
            return redirect()->route('admin.jobs.index')->with('success', 'Job đã được xóa!');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Lỗi: ' . $e->getMessage()]);
        }
    }

    private function saveJobAttributes($productId, $request)
    {
        $attributes = [
            40 => $request->job_type,
            41 => $request->experience_level,
            42 => $request->salary_range,
            43 => $request->job_location,
            45 => is_array($request->required_skills) ? implode(',', $request->required_skills) : $request->required_skills,
            48 => is_array($request->job_benefits) ? implode(',', $request->job_benefits) : $request->job_benefits,
            50 => $request->contact_email,
            51 => $request->contact_phone
        ];

        foreach ($attributes as $attributeId => $value) {
            if ($value) {
                DB::table('product_attribute_values')->insert([
                    'product_id' => $productId,
                    'attribute_id' => $attributeId,
                    'text_value' => $value
                ]);
            }
        }
    }

    private function getJobStats()
    {
        $userId = Auth::guard('admin')->id();
        
        $totalJobs = DB::table('products')
            ->where('type', 'job')
            ->where('created_by_admin_id', $userId)
            ->count();
        
        return [
            'total_jobs' => $totalJobs,
            'active_jobs' => $totalJobs,
            'pending_jobs' => 0,
            'total_applications' => 0
        ];
    }

    private function getUserJobs($page = 1)
    {
        $userId = Auth::guard('admin')->id();
        
        $jobs = DB::table('products')
            ->leftJoin('product_flat', 'products.id', '=', 'product_flat.product_id')
            ->leftJoin('companies', 'products.company_id', '=', 'companies.id')
            ->select('products.*', 'product_flat.name as title', 'product_flat.description', 'companies.name as company_name')
            ->where('products.type', 'job')
            ->where('products.created_by_admin_id', $userId)
            ->orderBy('products.created_at', 'desc')
            ->paginate(10, ['*'], 'page', $page);
            
        $jobs->getCollection()->transform(function($job) {
            if (!$job->title) {
                $job->title = 'Job #' . $job->id;
            }
            $job->status = 'active';
            $job->location = 'N/A';
            $job->applications_count = 0;
            return $job;
        });
        
        return ['data' => $jobs->items(), 'pagination' => $jobs];
    }

    private function uploadCompanyLogo($file)
    {
        try {
            if (!$file->isValid() || $file->getSize() > 2 * 1024 * 1024) {
                return null;
            }

            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file->getMimeType(), $allowedTypes)) {
                return null;
            }

            $filename = 'company_logo_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('company-logos', $filename, 'public');
            
            return $path;
            
        } catch (\Exception $e) {
            \Log::error('Logo upload failed: ' . $e->getMessage());
            return null;
        }
    }
}
