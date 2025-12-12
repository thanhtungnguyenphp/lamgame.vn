<?php

namespace Webkul\JobManagement\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Product\Models\Product;
use App\Models\Company;

class JobController extends Controller
{
    /**
     * Display a listing of jobs.
     */
    public function index()
    {
        $jobs = DB::table('products as p')
            ->leftJoin('product_flat as pf', 'p.id', '=', 'pf.product_id')
            ->leftJoin('companies as c', 'p.company_id', '=', 'c.id')
            ->leftJoin('admins as a', 'p.created_by_admin_id', '=', 'a.id')
            ->where('p.sku', 'LIKE', 'JOB_%')
            ->where('pf.locale', 'vi')
            ->select(
                'p.id',
                'p.sku',
                'pf.name',
                'pf.status',
                'c.name as company_name',
                'a.name as created_by',
                'p.created_at',
                'p.updated_at'
            )
            ->orderBy('p.created_at', 'desc')
            ->paginate(15);

        // Get statistics
        $totalJobs = DB::table('products as p')
            ->leftJoin('product_flat as pf', 'p.id', '=', 'pf.product_id')
            ->where('p.sku', 'LIKE', 'JOB_%')
            ->where('pf.locale', 'vi')
            ->count();

        $publishedJobs = DB::table('products as p')
            ->leftJoin('product_flat as pf', 'p.id', '=', 'pf.product_id')
            ->where('p.sku', 'LIKE', 'JOB_%')
            ->where('pf.locale', 'vi')
            ->where('pf.status', 1)
            ->count();

        $unpublishedJobs = $totalJobs - $publishedJobs;

        $thisWeekJobs = DB::table('products as p')
            ->leftJoin('product_flat as pf', 'p.id', '=', 'pf.product_id')
            ->where('p.sku', 'LIKE', 'JOB_%')
            ->where('pf.locale', 'vi')
            ->where('p.created_at', '>=', now()->subDays(7))
            ->count();

        $stats = [
            'total' => $totalJobs,
            'published' => $publishedJobs,
            'unpublished' => $unpublishedJobs,
            'thisWeek' => $thisWeekJobs
        ];

        return view('job_management::admin.jobs.index', compact('jobs', 'stats'));
    }

    /**
     * Show the form for creating a new job.
     */
    public function create()
    {
        $companies = Company::where('status', 1)->get();
        return view('job_management::admin.jobs.create', compact('companies'));
    }

    /**
     * Store a newly created job.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'description' => 'required|string',
            'job_type' => 'required|string',
            'experience_level' => 'required|string',
            'salary_range' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $sku = 'JOB_' . strtoupper(uniqid());
            
            $product = Product::create([
                'sku' => $sku,
                'type' => 'simple',
                'attribute_family_id' => 1,
                'company_id' => $request->company_id,
                'created_by_admin_id' => auth()->guard('admin')->id(),
            ]);

            // Create product flat
            DB::table('product_flat')->insert([
                'product_id' => $product->id,
                'sku' => $sku,
                'name' => $request->title,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'status' => 1,
                'visible_individually' => 1,
                'url_key' => \Str::slug($request->title) . '-' . $product->id,
                'locale' => 'vi',
                'channel' => 'default',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Save job attributes
            $this->saveJobAttributes($product->id, $request);

            DB::commit();

            session()->flash('success', trans('job_management::app.admin.jobs.create-success'));
            return redirect()->route('admin.jobs.index');

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', trans('job_management::app.admin.jobs.create-error'));
            return back()->withInput();
        }
    }

    /**
     * Display the specified job.
     */
    public function show($id)
    {
        $job = $this->getJobWithDetails($id);
        
        if (!$job) {
            session()->flash('error', trans('job_management::app.admin.jobs.not-found'));
            return redirect()->route('admin.jobs.index');
        }

        return view('job_management::admin.jobs.show', compact('job'));
    }

    /**
     * Show the form for editing the specified job.
     */
    public function edit($id)
    {
        $job = $this->getJobWithDetails($id);
        
        if (!$job) {
            session()->flash('error', trans('job_management::app.admin.jobs.not-found'));
            return redirect()->route('admin.jobs.index');
        }

        // Get job attributes
        $attributes = DB::table('product_attribute_values')
            ->where('product_id', $id)
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
        
        // Load admin's company
        $admin = auth()->guard('admin')->user();
        $company = null;
        
        if ($admin && $admin->company_id) {
            $company = Company::find($admin->company_id);
            
            // Load logo as base64 if exists
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

    /**
     * Update the specified job.
     */
    public function update(Request $request, $id)
    {
        \Log::info('Update job started', [
            'job_id' => $id,
            'request_data' => $request->all()
        ]);
        
        // Remove empty company_id if exists
        if ($request->has('company_id') && empty($request->company_id)) {
            $request->request->remove('company_id');
        }
        
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

        try {
            DB::beginTransaction();
            
            $admin = auth()->guard('admin')->user();
            $companyId = null;
            
            // Handle company data
            if ($request->has('company')) {
                $companyData = $request->company;
                
                // Handle logo upload
                if ($request->hasFile('company_logo')) {
                    $file = $request->file('company_logo');
                    $filename = 'company_logo_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('company-logos', $filename, 'public');
                    if ($path) {
                        $companyData['logo'] = $path;
                    }
                }
                
                if ($admin->company_id) {
                    // Update existing company
                    $company = Company::find($admin->company_id);
                    if ($company) {
                        $company->update($companyData);
                        $companyId = $company->id;
                    }
                } else {
                    // Create new company
                    $companyData['created_by_admin_id'] = $admin->id;
                    $company = Company::create($companyData);
                    $companyId = $company->id;
                    $admin->update(['company_id' => $company->id]);
                }
            } else if ($admin->company_id) {
                $companyId = $admin->company_id;
            }

            // Update product
            $updateData = ['updated_at' => now()];
            if ($companyId) {
                $updateData['company_id'] = $companyId;
            }
            Product::where('id', $id)->update($updateData);

            // Update product flat
            DB::table('product_flat')
                ->where('product_id', $id)
                ->update([
                    'name' => $request->title,
                    'description' => $request->description,
                    'short_description' => $request->short_description,
                    'updated_at' => now()
                ]);

            // Update job attributes
            $this->updateJobAttributes($id, $request);
            
            \Log::info('Job updated successfully', ['job_id' => $id]);

            DB::commit();

            session()->flash('success', 'Job đã được cập nhật thành công!');
            return redirect()->route('admin.jobs.index');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Update job failed', [
                'job_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Lỗi: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Remove the specified job.
     */
    public function destroy($id)
    {
        try {
            $product = Product::find($id);
            
            if (!$product) {
                return response()->json(['message' => trans('job_management::app.admin.jobs.not-found')], 404);
            }

            $product->delete();

            session()->flash('success', trans('job_management::app.admin.jobs.delete-success'));
            return response()->json(['message' => 'success']);

        } catch (\Exception $e) {
            return response()->json(['message' => trans('job_management::app.admin.jobs.delete-error')], 500);
        }
    }

    /**
     * Publish job
     */
    public function publish($id)
    {
        DB::table('product_flat')
            ->where('product_id', $id)
            ->update(['status' => 1]);

        session()->flash('success', trans('job_management::app.admin.jobs.publish-success'));
        return back();
    }

    /**
     * Unpublish job
     */
    public function unpublish($id)
    {
        DB::table('product_flat')
            ->where('product_id', $id)
            ->update(['status' => 0]);

        session()->flash('success', trans('job_management::app.admin.jobs.unpublish-success'));
        return back();
    }

    /**
     * Mass update jobs
     */
    public function massUpdate(Request $request)
    {
        $jobIds = $request->indices;
        $action = $request->update_option;

        if ($action == 'publish') {
            DB::table('product_flat')
                ->whereIn('product_id', $jobIds)
                ->update(['status' => 1]);
        } elseif ($action == 'unpublish') {
            DB::table('product_flat')
                ->whereIn('product_id', $jobIds)
                ->update(['status' => 0]);
        }

        session()->flash('success', trans('job_management::app.admin.jobs.mass-update-success'));
        return back();
    }

    /**
     * Mass delete jobs
     */
    public function massDelete(Request $request)
    {
        Product::whereIn('id', $request->indices)->delete();

        session()->flash('success', trans('job_management::app.admin.jobs.mass-delete-success'));
        return back();
    }

    /**
     * Get job with details
     */
    private function getJobWithDetails($id)
    {
        return DB::table('products as p')
            ->leftJoin('product_flat as pf', 'p.id', '=', 'pf.product_id')
            ->leftJoin('companies as c', 'p.company_id', '=', 'c.id')
            ->where('p.id', $id)
            ->where('pf.locale', 'vi')
            ->select(
                'p.*',
                'pf.name',
                'pf.description',
                'pf.short_description',
                'pf.status',
                'c.name as company_name',
                'c.id as company_id'
            )
            ->first();
    }

    /**
     * Save job attributes
     */
    private function saveJobAttributes($productId, $request)
    {
        $attributes = [
            40 => $request->job_type,
            41 => $request->experience_level,
            42 => $request->salary_range,
            43 => $request->job_location,
            44 => $request->education_level,
            45 => is_array($request->required_skills) ? implode(',', $request->required_skills) : $request->required_skills,
            46 => $request->application_method,
            47 => $request->english_level,
            48 => is_array($request->job_benefits) ? implode(',', $request->job_benefits) : $request->job_benefits,
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
    }

    /**
     * Update job attributes
     */
    private function updateJobAttributes($productId, $request)
    {
        // Delete existing attributes
        DB::table('product_attribute_values')
            ->where('product_id', $productId)
            ->whereIn('attribute_id', [40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51])
            ->delete();

        // Save new attributes
        $this->saveJobAttributes($productId, $request);
    }
}
