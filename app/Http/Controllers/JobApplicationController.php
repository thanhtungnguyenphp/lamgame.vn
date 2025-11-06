<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    private $apiBase = 'https://lamgame.localhost/api';

    public function index(Request $request)
    {
        $response = Http::get("{$this->apiBase}/job-applications", [
            'employer_id' => Auth::id(),
            'status' => $request->get('status'),
            'page' => $request->get('page', 1)
        ]);
        
        $applications = $response->json();
        return view('job-dashboard.applications.index', compact('applications'));
    }

    public function show($id)
    {
        $response = Http::get("{$this->apiBase}/job-applications/{$id}");
        $application = $response->json();
        
        return view('job-dashboard.applications.show', compact('application'));
    }

    public function updateStatus(Request $request, $id)
    {
        $response = Http::put("{$this->apiBase}/job-applications/{$id}/status", [
            'status' => $request->status,
            'notes' => $request->notes
        ]);
        
        if ($response->successful()) {
            return back()->with('success', 'Trạng thái application đã được cập nhật!');
        }
        
        return back()->withErrors(['error' => 'Có lỗi xảy ra']);
    }
}
