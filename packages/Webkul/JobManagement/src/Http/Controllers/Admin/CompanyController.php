<?php

namespace Webkul\JobManagement\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Webkul\Admin\Http\Controllers\Controller;
use App\Models\Company;

class CompanyController extends Controller
{
    /**
     * Display a listing of companies.
     */
    public function index()
    {
        $companies = Company::with('createdBy')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('job_management::admin.companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new company.
     */
    public function create()
    {
        return view('job_management::admin.companies.create');
    }

    /**
     * Store a newly created company.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'website' => 'nullable|url',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'employee_count' => 'nullable|integer|min:1',
            'founded_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'industry' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $data = $request->all();
            $data['created_by_admin_id'] = auth()->guard('admin')->id();

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logoPath = $this->uploadLogo($request->file('logo'));
                if ($logoPath) {
                    $data['logo'] = $logoPath;
                }
            }

            Company::create($data);

            session()->flash('success', trans('job_management::app.admin.companies.create-success'));
            return redirect()->route('admin.companies.index');

        } catch (\Exception $e) {
            session()->flash('error', trans('job_management::app.admin.companies.create-error'));
            return back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified company.
     */
    public function edit($id)
    {
        $company = Company::find($id);
        
        if (!$company) {
            session()->flash('error', trans('job_management::app.admin.companies.not-found'));
            return redirect()->route('admin.companies.index');
        }

        return view('job_management::admin.companies.edit', compact('company'));
    }

    /**
     * Update the specified company.
     */
    public function update(Request $request, $id)
    {
        $company = Company::find($id);
        
        if (!$company) {
            session()->flash('error', trans('job_management::app.admin.companies.not-found'));
            return redirect()->route('admin.companies.index');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'website' => 'nullable|url',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'employee_count' => 'nullable|integer|min:1',
            'founded_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'industry' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $data = $request->all();

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logoPath = $this->uploadLogo($request->file('logo'));
                if ($logoPath) {
                    $data['logo'] = $logoPath;
                }
            }

            $company->update($data);

            session()->flash('success', trans('job_management::app.admin.companies.update-success'));
            return redirect()->route('admin.companies.index');

        } catch (\Exception $e) {
            session()->flash('error', trans('job_management::app.admin.companies.update-error'));
            return back()->withInput();
        }
    }

    /**
     * Remove the specified company.
     */
    public function destroy($id)
    {
        try {
            $company = Company::find($id);
            
            if (!$company) {
                return response()->json(['message' => trans('job_management::app.admin.companies.not-found')], 404);
            }

            $company->delete();

            session()->flash('success', trans('job_management::app.admin.companies.delete-success'));
            return response()->json(['message' => 'success']);

        } catch (\Exception $e) {
            return response()->json(['message' => trans('job_management::app.admin.companies.delete-error')], 500);
        }
    }

    /**
     * Upload company logo
     */
    private function uploadLogo($file)
    {
        try {
            $filename = 'company_logo_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('company-logos', $filename, 'public');
            return $path;
        } catch (\Exception $e) {
            \Log::error('Logo upload failed: ' . $e->getMessage());
            return null;
        }
    }
}
