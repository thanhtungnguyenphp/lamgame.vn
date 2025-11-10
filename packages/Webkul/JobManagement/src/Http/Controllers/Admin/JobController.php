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

        return view('job_management::admin.jobs.index', compact('jobs'));
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
        $companies = Company::where('status', 1)->get();
        
        if (!$job) {
            session()->flash('error', trans('job_management::app.admin.jobs.not-found'));
            return redirect()->route('admin.jobs.index');
        }

        // Get job attributes
        $attributes = DB::table('product_attribute_values')
            ->where('product_id', $id)
            ->pluck('text_value', 'attribute_id');

        return view('job_management::admin.jobs.edit', compact('job', 'companies', 'attributes'));
    }

    /**
     * Update the specified job.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'description' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // Update product
            Product::where('id', $id)->update([
                'company_id' => $request->company_id,
                'updated_at' => now()
            ]);

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

            DB::commit();

            session()->flash('success', trans('job_management::app.admin.jobs.update-success'));
            return redirect()->route('admin.jobs.index');

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', trans('job_management::app.admin.jobs.update-error'));
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
                    'text_value' => $value,
                    'created_at' => now(),
                    'updated_at' => now()
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
            ->whereIn('attribute_id', [40, 41, 42, 43, 45, 48, 50, 51])
            ->delete();

        // Save new attributes
        $this->saveJobAttributes($productId, $request);
    }
}
