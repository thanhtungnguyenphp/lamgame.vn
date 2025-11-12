<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::where('created_by_admin_id', Auth::guard('admin')->id())
                           ->orderBy('created_at', 'desc')
                           ->paginate(20);
        
        return view('admin.companies.index', compact('companies'));
    }

    public function show($id)
    {
        $company = Company::where('id', $id)
                         ->where('created_by_admin_id', Auth::guard('admin')->id())
                         ->firstOrFail();
        
        return view('admin.companies.show', compact('company'));
    }

    public function edit($id)
    {
        $company = Company::where('id', $id)
                         ->where('created_by_admin_id', Auth::guard('admin')->id())
                         ->firstOrFail();
        
        return view('admin.companies.edit', compact('company'));
    }

    public function update(Request $request, $id)
    {
        $company = Company::where('id', $id)
                         ->where('created_by_admin_id', Auth::guard('admin')->id())
                         ->firstOrFail();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'website' => 'nullable|url',
            'description' => 'nullable|string'
        ]);

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('company-logos', 'public');
            $data['logo'] = $logoPath;
        }

        $company->update($data);

        return redirect()->route('admin.companies.index')
                        ->with('success', 'Công ty đã được cập nhật thành công!');
    }
}
