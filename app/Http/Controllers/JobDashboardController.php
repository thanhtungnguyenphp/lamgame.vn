<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Company;

class JobDashboardController extends Controller
{
    private $apiBase = 'https://lamgame.localhost/api';

    public function index()
    {
        $stats = $this->getJobStats();
        $recentJobs = $this->getRecentJobs();
        
        return view('job-dashboard.index', compact('stats', 'recentJobs'));
    }

    public function jobs(Request $request)
    {
        $jobs = $this->getUserJobs($request->get('page', 1));
        return view('job-dashboard.jobs', compact('jobs'));
    }

    public function create()
    {
        $admin = Auth::guard('admin')->user();
        $company = null;
        
        if ($admin && $admin->company_id) {
            $company = Company::find($admin->company_id);
        }
        
        return view('job-dashboard.create', compact('company'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $admin = Auth::guard('admin')->user();
            $companyId = null;
            
            // Handle company logic
            if ($request->has('company')) {
                if ($admin->company_id) {
                    // Update existing company
                    $company = Company::find($admin->company_id);
                    if ($company) {
                        $company->update($request->company);
                        $companyId = $company->id;
                    }
                } else {
                    // Create new company
                    $companyData = $request->company;
                    $companyData['created_by_admin_id'] = $admin->id;
                    
                    $company = Company::create($companyData);
                    $companyId = $company->id;
                    
                    // Link admin to company
                    $admin->update(['company_id' => $company->id]);
                }
            } else if ($admin->company_id) {
                // Use existing company
                $companyId = $admin->company_id;
            }
            
            $sku = 'JOB_' . strtoupper(uniqid());
            
            // Tạo product
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
            
            // Tạo product_flat với product_id
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

            // Lưu job attributes vào product_attribute_values
            $this->saveJobAttributes($productId, $request);
            
            DB::commit();
            return redirect()->route('job.dashboard.jobs')->with('success', 'Job đã được tạo thành công!');
            
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

    public function edit($id)
    {
        $job = DB::table('products')
            ->leftJoin('product_flat', 'products.id', '=', 'product_flat.product_id')
            ->select('products.*', 'product_flat.name', 'product_flat.description', 'product_flat.short_description')
            ->where('products.id', $id)
            ->where('products.created_by_admin_id', Auth::guard('admin')->id())
            ->first();
            
        if (!$job) {
            return redirect()->route('job.dashboard.jobs')->withErrors(['error' => 'Job không tồn tại']);
        }

        // Lấy job attributes
        $attributes = DB::table('product_attribute_values')
            ->where('product_id', $id)
            ->whereIn('attribute_id', [40, 41, 42, 43, 45, 48, 50, 51])
            ->pluck('text_value', 'attribute_id');
        
        return view('job-dashboard.edit', compact('job', 'attributes'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            // Cập nhật product_flat
            DB::table('product_flat')
                ->where('product_id', $id)
                ->update([
                    'name' => $request->title,
                    'description' => $request->description,
                    'short_description' => $request->short_description,
                    'updated_at' => now()
                ]);
            
            // Cập nhật products
            DB::table('products')
                ->where('id', $id)
                ->where('created_by_admin_id', Auth::guard('admin')->id())
                ->update(['updated_at' => now()]);

            // Cập nhật job attributes
            DB::table('product_attribute_values')->where('product_id', $id)->delete();
            $this->saveJobAttributes($id, $request);
            
            DB::commit();
            return redirect()->route('job.dashboard.jobs')->with('success', 'Job đã được cập nhật!');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Lỗi: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            // Xóa job attributes
            DB::table('product_attribute_values')->where('product_id', $id)->delete();
            
            // Xóa product_flat
            DB::table('product_flat')->where('product_id', $id)->delete();
            
            // Xóa product
            $deleted = DB::table('products')
                ->where('id', $id)
                ->where('created_by_admin_id', Auth::guard('admin')->id())
                ->delete();
                
            if (!$deleted) {
                throw new \Exception('Job không tồn tại hoặc bạn không có quyền xóa');
            }
            
            DB::commit();
            return redirect()->route('job.dashboard.jobs')->with('success', 'Job đã được xóa!');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Lỗi: ' . $e->getMessage()]);
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
            'total_applications' => 0,
            'new_applications' => 0
        ];
    }

    private function getRecentJobs()
    {
        $userId = Auth::guard('admin')->id();
        
        return DB::table('products')
            ->leftJoin('product_flat', 'products.id', '=', 'product_flat.product_id')
            ->select('products.*', 'product_flat.name', 'product_flat.description')
            ->where('products.type', 'job')
            ->where('products.created_by_admin_id', $userId)
            ->orderBy('products.created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($job) {
                if (!$job->name) {
                    $job->name = 'Job #' . $job->id;
                }
                return $job;
            });
    }

    private function getUserJobs($page = 1)
    {
        $userId = Auth::guard('admin')->id();
        
        $jobs = DB::table('products')
            ->leftJoin('product_flat', 'products.id', '=', 'product_flat.product_id')
            ->select('products.*', 'product_flat.name', 'product_flat.description')
            ->where('products.type', 'job')
            ->where('products.created_by_admin_id', $userId)
            ->orderBy('products.created_at', 'desc')
            ->paginate(10, ['*'], 'page', $page);
            
        $jobs->getCollection()->transform(function($job) {
            if (!$job->name) {
                $job->name = 'Job #' . $job->id;
            }
            return $job;
        });
        
        return $jobs;
    }
}
