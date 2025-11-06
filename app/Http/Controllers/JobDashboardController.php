<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        return view('job-dashboard.create');
    }

    public function store(Request $request)
    {
        $jobData = [
            'name' => $request->title,
            'description' => $request->description,
            'type' => 'job',
            'sku' => 'JOB_' . strtoupper(uniqid()),
            'status' => 1,
            'created_by_admin_id' => Auth::guard('admin')->id()
        ];

        // Tạo job trực tiếp vào database
        $jobId = DB::table('products')->insertGetId($jobData);
        
        if ($jobId) {
            return redirect()->route('job.dashboard.jobs')->with('success', 'Job đã được tạo thành công!');
        }
        
        return back()->withErrors(['error' => 'Có lỗi xảy ra khi tạo job']);
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
            ->where('type', 'job')
            ->where('created_by_admin_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    private function getUserJobs($page = 1)
    {
        $userId = Auth::guard('admin')->id();
        
        return DB::table('products')
            ->where('type', 'job')
            ->where('created_by_admin_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'page', $page);
    }
}
