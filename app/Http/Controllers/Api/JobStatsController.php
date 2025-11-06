<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobStatsController extends Controller
{
    public function getStats(Request $request)
    {
        $userId = $request->get('user_id');
        
        $stats = [
            'total_jobs' => DB::table('products')
                ->where('type', 'job')
                ->where('created_by', $userId)
                ->count(),
                
            'active_jobs' => DB::table('products')
                ->where('type', 'job')
                ->where('created_by', $userId)
                ->where('status', 1)
                ->count(),
                
            'total_applications' => DB::table('job_applications')
                ->whereIn('job_id', function($query) use ($userId) {
                    $query->select('id')
                          ->from('products')
                          ->where('type', 'job')
                          ->where('created_by', $userId);
                })
                ->count(),
                
            'new_applications' => DB::table('job_applications')
                ->whereIn('job_id', function($query) use ($userId) {
                    $query->select('id')
                          ->from('products')
                          ->where('type', 'job')
                          ->where('created_by', $userId);
                })
                ->where('status', 'pending')
                ->where('created_at', '>=', now()->subDays(7))
                ->count()
        ];
        
        return response()->json($stats);
    }
}
