<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ApplicationActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    protected ApplicationActivityService $activityService;
    
    public function __construct(ApplicationActivityService $activityService)
    {
        $this->activityService = $activityService;
    }
    public function index(Request $request)
    {
        $jobId = $request->get('job_id');
        $applications = $this->getApplications($jobId);
        $jobs = $this->getUserJobs();
        
        return view('admin.applications.index', compact('applications', 'jobs', 'jobId'));
    }

    public function show($id)
    {
        $application = $this->getApplicationById($id);
        
        if (!$application) {
            return redirect()->route('admin.applications.index')
                ->withErrors(['error' => 'Ứng viên không tồn tại']);
        }
        
        // Log viewed activity
        $this->activityService->logViewed($id);
        
        // Get activities
        $activities = $this->activityService->getActivities($id);
        
        return view('admin.applications.show', compact('application', 'activities'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,shortlisted,rejected,accepted',
            'employer_notes' => 'nullable|string|max:2000',
        ]);

        try {
            // Get current application
            $current = DB::table('job_applications')->where('id', $id)->first();
            
            if (!$current) {
                throw new \Exception('Ứng viên không tồn tại');
            }
            
            $updated = DB::table('job_applications')
                ->where('id', $id)
                ->whereIn('job_id', function($query) {
                    $query->select('id')
                          ->from('products')
                          ->where('created_by_admin_id', Auth::guard('admin')->id())
                          ->where('type', 'job');
                })
                ->update([
                    'status' => $request->status,
                    'employer_notes' => $request->employer_notes,
                    'updated_at' => now(),
                ]);
                
            if (!$updated) {
                throw new \Exception('Ứng viên không tồn tại hoặc bạn không có quyền cập nhật');
            }
            
            // Log status change
            if ($current->status !== $request->status) {
                $this->activityService->logStatusChanged($id, $current->status, $request->status);
            }
            
            // Log note change
            if ($current->employer_notes !== $request->employer_notes) {
                $this->activityService->logNoteChanged($id, $current->employer_notes, $request->employer_notes);
            }
            
            return redirect()->route('admin.applications.show', $id)
                ->with('success', 'Đã cập nhật trạng thái và ghi chú thành công!');
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Lỗi: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $deleted = DB::table('job_applications')
                ->where('id', $id)
                ->whereIn('job_id', function($query) {
                    $query->select('id')
                          ->from('products')
                          ->where('created_by_admin_id', Auth::guard('admin')->id())
                          ->where('type', 'job');
                })
                ->delete();
                
            if (!$deleted) {
                throw new \Exception('Ứng viên không tồn tại hoặc bạn không có quyền xóa');
            }
            
            return redirect()->route('admin.applications.index')
                ->with('success', 'Đã xóa ứng viên thành công!');
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Lỗi: ' . $e->getMessage()]);
        }
    }

    public function downloadCV($id)
    {
        $application = $this->getApplicationById($id);
        
        if (!$application) {
            abort(404, 'Ứng viên không tồn tại');
        }
        
        if (!$application->resume_file_path) {
            abort(404, 'CV không tồn tại');
        }
        
        $filePath = storage_path('app/private/' . $application->resume_file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File CV không tồn tại');
        }
        
        // Log CV downloaded
        $this->activityService->logCVDownloaded($id);
        
        return response()->download($filePath);
    }

    private function getApplications($jobId = null)
    {
        $query = DB::table('job_applications')
            ->leftJoin('products', 'job_applications.job_id', '=', 'products.id')
            ->leftJoin('product_flat', 'products.id', '=', 'product_flat.product_id')
            ->select(
                'job_applications.*',
                'product_flat.name as job_title'
            )
            ->whereIn('job_applications.job_id', function($subQuery) {
                $subQuery->select('id')
                         ->from('products')
                         ->where('created_by_admin_id', Auth::guard('admin')->id())
                         ->where('type', 'job');
            });

        if ($jobId) {
            $query->where('job_applications.job_id', $jobId);
        }

        return $query->orderBy('job_applications.created_at', 'desc')->paginate(20);
    }

    private function getApplicationById($id)
    {
        return DB::table('job_applications')
            ->leftJoin('products', 'job_applications.job_id', '=', 'products.id')
            ->leftJoin('product_flat', 'products.id', '=', 'product_flat.product_id')
            ->select(
                'job_applications.*',
                'product_flat.name as job_title'
            )
            ->where('job_applications.id', $id)
            ->whereIn('job_applications.job_id', function($subQuery) {
                $subQuery->select('id')
                         ->from('products')
                         ->where('created_by_admin_id', Auth::guard('admin')->id())
                         ->where('type', 'job');
            })
            ->first();
    }

    private function getUserJobs()
    {
        return DB::table('products')
            ->leftJoin('product_flat', 'products.id', '=', 'product_flat.product_id')
            ->select('products.id', 'product_flat.name as title')
            ->where('products.type', 'job')
            ->where('products.created_by_admin_id', Auth::guard('admin')->id())
            ->orderBy('product_flat.name')
            ->get();
    }
}
