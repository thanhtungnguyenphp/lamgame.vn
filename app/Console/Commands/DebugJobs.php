<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DebugJobs extends Command
{
    protected $signature = 'debug:jobs {admin_id?}';
    protected $description = 'Debug job listings difference between admin and frontend';

    public function handle()
    {
        $adminId = $this->argument('admin_id') ?? 1;
        
        $this->info("Checking jobs for admin_id: {$adminId}");
        $this->newLine();
        
        // Admin query
        $this->info("=== ADMIN QUERY ===");
        $adminJobs = DB::table('products')
            ->leftJoin('product_flat', function($join) {
                $join->on('products.id', '=', 'product_flat.product_id')
                     ->where('product_flat.locale', '=', 'vi');
            })
            ->select('products.id', 'products.sku', 'products.type', 'products.created_by_admin_id', 
                     'product_flat.name', 'product_flat.status', 'product_flat.visible_individually')
            ->where('products.sku', 'LIKE', 'JOB_%')
            ->where('products.created_by_admin_id', $adminId)
            ->get();
        
        $this->table(
            ['ID', 'SKU', 'Type', 'Admin', 'Name', 'Status', 'Visible'],
            $adminJobs->map(fn($j) => [
                $j->id,
                $j->sku,
                $j->type ?? 'NULL',
                $j->created_by_admin_id ?? 'NULL',
                substr($j->name ?? 'NULL', 0, 30),
                $j->status ?? 'NULL',
                $j->visible_individually ?? 'NULL'
            ])
        );
        $this->info("Admin jobs count: " . $adminJobs->count());
        $this->newLine();
        
        // Frontend query
        $this->info("=== FRONTEND QUERY ===");
        $frontendJobs = DB::table('products as p')
            ->leftJoin('product_flat as pf', function($join) {
                $join->on('p.id', '=', 'pf.product_id')
                     ->where('pf.locale', '=', 'vi');
            })
            ->select('p.id', 'p.sku', 'p.type', 'p.created_by_admin_id', 
                     'pf.name', 'pf.status', 'pf.visible_individually')
            ->where('p.type', 'job')
            ->where('p.sku', 'LIKE', 'JOB_%')
            ->where('pf.status', 1)
            ->where('pf.visible_individually', 1)
            ->get();
        
        $this->table(
            ['ID', 'SKU', 'Type', 'Admin', 'Name', 'Status', 'Visible'],
            $frontendJobs->map(fn($j) => [
                $j->id,
                $j->sku,
                $j->type ?? 'NULL',
                $j->created_by_admin_id ?? 'NULL',
                substr($j->name ?? 'NULL', 0, 30),
                $j->status ?? 'NULL',
                $j->visible_individually ?? 'NULL'
            ])
        );
        $this->info("Frontend jobs count: " . $frontendJobs->count());
        $this->newLine();
        
        // All jobs
        $this->info("=== ALL JOBS (SKU LIKE JOB_% OR type = job) ===");
        $allJobs = DB::table('products as p')
            ->leftJoin('product_flat as pf', function($join) {
                $join->on('p.id', '=', 'pf.product_id')
                     ->where('pf.locale', '=', 'vi');
            })
            ->select('p.id', 'p.sku', 'p.type', 'p.created_by_admin_id', 
                     'pf.name', 'pf.status', 'pf.visible_individually')
            ->where(function($q) {
                $q->where('p.sku', 'LIKE', 'JOB_%')
                  ->orWhere('p.type', 'job');
            })
            ->get();
        
        $this->table(
            ['ID', 'SKU', 'Type', 'Admin', 'Name', 'Status', 'Visible'],
            $allJobs->map(fn($j) => [
                $j->id,
                $j->sku,
                $j->type ?? 'NULL',
                $j->created_by_admin_id ?? 'NULL',
                substr($j->name ?? 'NULL', 0, 30),
                $j->status ?? 'NULL',
                $j->visible_individually ?? 'NULL'
            ])
        );
        $this->info("All jobs count: " . $allJobs->count());
        $this->newLine();
        
        // Analysis
        $this->info("=== ANALYSIS ===");
        $adminIds = $adminJobs->pluck('id');
        $frontendIds = $frontendJobs->pluck('id');
        
        $inFrontendNotAdmin = $frontendIds->diff($adminIds);
        $inAdminNotFrontend = $adminIds->diff($frontendIds);
        
        if ($inFrontendNotAdmin->isNotEmpty()) {
            $this->warn("Jobs in FRONTEND but NOT in ADMIN:");
            $jobs = $allJobs->whereIn('id', $inFrontendNotAdmin);
            foreach ($jobs as $job) {
                $reasons = [];
                if ($job->created_by_admin_id != $adminId) {
                    $reasons[] = "Different admin ({$job->created_by_admin_id})";
                }
                if (!str_starts_with($job->sku, 'JOB_')) {
                    $reasons[] = "SKU doesn't start with JOB_";
                }
                $this->line("  - ID {$job->id}: {$job->name} | Reason: " . implode(', ', $reasons));
            }
        } else {
            $this->info("No jobs in frontend that aren't in admin");
        }
        
        $this->newLine();
        
        if ($inAdminNotFrontend->isNotEmpty()) {
            $this->warn("Jobs in ADMIN but NOT in FRONTEND:");
            $jobs = $allJobs->whereIn('id', $inAdminNotFrontend);
            foreach ($jobs as $job) {
                $reasons = [];
                if ($job->type != 'job') {
                    $reasons[] = "Type is '{$job->type}' not 'job'";
                }
                if (!str_starts_with($job->sku, 'JOB_')) {
                    $reasons[] = "SKU doesn't start with JOB_";
                }
                if ($job->status != 1) {
                    $reasons[] = "Status is {$job->status} (not published)";
                }
                if ($job->visible_individually != 1) {
                    $reasons[] = "Not visible individually";
                }
                $this->line("  - ID {$job->id}: {$job->name} | Reason: " . implode(', ', $reasons));
            }
        } else {
            $this->info("No jobs in admin that aren't in frontend");
        }
        
        return 0;
    }
}
