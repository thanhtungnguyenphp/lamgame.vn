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
        // Validate request
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string',
            'job_type' => 'required',
            'experience_level' => 'required',
            'job_location' => 'required',
            'contact_email' => 'required|email',
            'company.name' => 'required|string|max:255',
        ], [
            'title.required' => 'Tiêu đề job là bắt buộc',
            'description.required' => 'Mô tả chi tiết là bắt buộc',
            'contact_email.required' => 'Email liên hệ là bắt buộc',
            'contact_email.email' => 'Email liên hệ không hợp lệ',
            'company.name.required' => 'Tên công ty là bắt buộc',
        ]);
        
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
            
            if (!$productId) {
                throw new \Exception('Failed to create job product');
            }
            
            $flatInserted = DB::table('product_flat')->insert([
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
            
            if (!$flatInserted) {
                throw new \Exception('Failed to create job product_flat record');
            }

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
        
        \Log::info('Edit job - Admin info', [
            'admin_id' => $admin->id,
            'admin_name' => $admin->name,
            'admin_company_id' => $admin->company_id,
            'job_id' => $id
        ]);
        
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
            ->whereIn('attribute_id', [40, 41, 42, 43, 44, 45, 46, 47, 48, 49])
            ->pluck('text_value', 'attribute_id');

        // Convert single-value attributes to integers
        foreach ([40, 41, 42, 43, 44, 46, 47, 49] as $attrId) {
            if (isset($attributes[$attrId]) && is_numeric($attributes[$attrId])) {
                $attributes[$attrId] = (int) $attributes[$attrId];
            }
        }

        // Get skills and benefits
        $skillsValue = $attributes[45] ?? '';
        $benefitsValue = $attributes[48] ?? '';
        
        $skills = $skillsValue ? array_map('intval', explode(',', $skillsValue)) : [];
        $benefits = $benefitsValue ? array_map('intval', explode(',', $benefitsValue)) : [];

        $company = null;
        
        \Log::info('Before loading company', [
            'admin_exists' => $admin ? 'yes' : 'no',
            'admin_company_id' => $admin ? $admin->company_id : 'N/A',
            'admin_company_id_type' => $admin && $admin->company_id ? gettype($admin->company_id) : 'N/A'
        ]);
        
        if ($admin && $admin->company_id) {
            $company = Company::find($admin->company_id);
            
            \Log::info('Company loaded for edit', [
                'company_id' => $company ? $company->id : 'not found',
                'company_name' => $company ? $company->name : 'N/A',
                'has_logo' => $company && $company->logo ? 'yes' : 'no',
                'company_object' => $company ? json_encode($company->toArray()) : 'null'
            ]);
            
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
        
        return view('admin.jobs.edit', compact('job', 'attributes', 'company', 'skills', 'benefits'));
    }

    public function update(Request $request, $id)
    {
        // Remove empty company_id if exists (might come from hidden input)
        if ($request->has('company_id') && empty($request->company_id)) {
            $request->request->remove('company_id');
        }
        
        // Debug: Check what's in the request
        \Log::info('Update job request data:', $request->all());
        
        // Validate request
        try {
            $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string',
            'job_type' => 'required',
            'experience_level' => 'required',
            'job_location' => 'required',
            'contact_email' => 'required|email',
            'company.name' => 'required_if:company,array|string|max:255',
        ], [
            'title.required' => 'Tiêu đề job là bắt buộc',
            'description.required' => 'Mô tả chi tiết là bắt buộc',
            'contact_email.required' => 'Email liên hệ là bắt buộc',
            'contact_email.email' => 'Email liên hệ không hợp lệ',
            'company.name.required_if' => 'Tên công ty là bắt buộc',
        ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed:', $e->errors());
            throw $e;
        }
        
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
            DB::table('job_skills')->where('product_id', $id)->delete();
            DB::table('job_benefits')->where('product_id', $id)->delete();
            
            $this->saveJobAttributes($id, $request);
            
            DB::commit();
            return redirect()->route('admin.jobs.index')->with('success', 'Job đã được cập nhật!');
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Job update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return back()->withErrors(['error' => 'Lỗi: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            DB::table('product_attribute_values')->where('product_id', $id)->delete();
            DB::table('job_skills')->where('product_id', $id)->delete();
            DB::table('job_benefits')->where('product_id', $id)->delete();
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
            44 => $request->education_level,
            46 => $request->application_method,
            47 => $request->english_level,
            49 => $request->company_size,
            50 => $request->contact_email,
            51 => $request->contact_phone
        ];

        foreach ($attributes as $attributeId => $value) {
            if ($value) {
                DB::table('product_attribute_values')->insert([
                    'product_id' => $productId,
                    'attribute_id' => $attributeId,
                    'text_value' => $value,
                    'locale' => 'vi',
                    'channel' => 'default'
                ]);
            }
        }
        
        // Save skills using pivot table AND product_attribute_values
        if ($request->has('required_skills') && is_array($request->required_skills)) {
            // Save to product_attribute_values (for compatibility)
            DB::table('product_attribute_values')->insert([
                'product_id' => $productId,
                'attribute_id' => 45,
                'text_value' => implode(',', $request->required_skills),
                'locale' => 'vi',
                'channel' => 'default'
            ]);
            
            // Save to pivot table
            $skillsData = array_map(function($skillId) use ($productId) {
                return [
                    'product_id' => $productId,
                    'skill_option_id' => $skillId,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }, $request->required_skills);
            
            DB::table('job_skills')->insert($skillsData);
        }
        
        // Save benefits using pivot table AND product_attribute_values
        if ($request->has('job_benefits') && is_array($request->job_benefits)) {
            // Save to product_attribute_values (for compatibility)
            DB::table('product_attribute_values')->insert([
                'product_id' => $productId,
                'attribute_id' => 48,
                'text_value' => implode(',', $request->job_benefits),
                'locale' => 'vi',
                'channel' => 'default'
            ]);
            
            // Save to pivot table
            $benefitsData = array_map(function($benefitId) use ($productId) {
                return [
                    'product_id' => $productId,
                    'benefit_option_id' => $benefitId,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }, $request->job_benefits);
            
            DB::table('job_benefits')->insert($benefitsData);
        }
    }

    private function getJobStats()
    {
        $userId = Auth::guard('admin')->id();
        
        $totalJobs = DB::table('products')
            ->join('product_flat', function($join) {
                $join->on('products.id', '=', 'product_flat.product_id')
                     ->where('product_flat.locale', '=', 'vi');
            })
            ->where('products.sku', 'LIKE', 'JOB_%')
            ->where('products.created_by_admin_id', $userId)
            ->count();
        
        $publishedJobs = DB::table('products')
            ->join('product_flat', function($join) {
                $join->on('products.id', '=', 'product_flat.product_id')
                     ->where('product_flat.locale', '=', 'vi');
            })
            ->where('products.sku', 'LIKE', 'JOB_%')
            ->where('products.created_by_admin_id', $userId)
            ->where('product_flat.status', 1)
            ->where('product_flat.visible_individually', 1)
            ->count();
        
        // Count total applications for user's jobs
        $totalApplications = DB::table('job_applications')
            ->whereIn('job_id', function($query) use ($userId) {
                $query->select('id')
                      ->from('products')
                      ->where('created_by_admin_id', $userId)
                      ->where('sku', 'LIKE', 'JOB_%');
            })
            ->count();
        
        return [
            'total_jobs' => $totalJobs,
            'active_jobs' => $publishedJobs,
            'pending_jobs' => $totalJobs - $publishedJobs,
            'total_applications' => $totalApplications
        ];
    }

    private function getUserJobs($page = 1)
    {
        $userId = Auth::guard('admin')->id();
        
        $jobs = DB::table('products')
            ->join('product_flat', function($join) {
                $join->on('products.id', '=', 'product_flat.product_id')
                     ->where('product_flat.locale', '=', 'vi');
            })
            ->leftJoin('companies', 'products.company_id', '=', 'companies.id')
            ->select('products.*', 'product_flat.name as title', 'product_flat.description', 'companies.name as company_name', 'product_flat.status as publish_status', 'product_flat.visible_individually')
            ->where('products.sku', 'LIKE', 'JOB_%')
            ->where('products.created_by_admin_id', $userId)
            ->orderBy('products.created_at', 'desc')
            ->paginate(10, ['*'], 'page', $page);
        
        // Get applications count for all jobs in this page
        $jobIds = collect($jobs->items())->pluck('id')->toArray();
        $applicationsCounts = DB::table('job_applications')
            ->select('job_id', DB::raw('COUNT(*) as count'))
            ->whereIn('job_id', $jobIds)
            ->groupBy('job_id')
            ->pluck('count', 'job_id');
            
        $jobs->getCollection()->transform(function($job) use ($applicationsCounts) {
            if (!$job->title) {
                $job->title = 'Job #' . $job->id;
            }
            $job->status = $job->publish_status == 1 ? 'published' : 'draft';
            $job->location = 'N/A';
            $job->applications_count = $applicationsCounts[$job->id] ?? 0;
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
            
            // Magic bytes validation
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file->getRealPath());
            finfo_close($finfo);
            
            if (!in_array($mimeType, $allowedTypes)) {
                \Log::warning('File mime type mismatch', [
                    'reported' => $file->getMimeType(),
                    'actual' => $mimeType
                ]);
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
