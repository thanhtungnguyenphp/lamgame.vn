<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateJobUrlKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:update-url-keys';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Update url_key for job products that are missing them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating job url_keys...');
        
        // Get jobs with missing or empty url_keys
        $jobs = DB::table('products as p')
            ->leftJoin('product_flat as pf', function($join) {
                $join->on('p.id', '=', 'pf.product_id')
                     ->where('pf.locale', '=', 'vi');
            })
            ->where('p.sku', 'LIKE', 'JOB_%')
            ->where('pf.status', 1)
            ->where('pf.visible_individually', 1)
            ->where(function($query) {
                $query->whereNull('pf.url_key')
                      ->orWhere('pf.url_key', '=', '');
            })
            ->select('p.id', 'pf.id as flat_id', 'pf.name')
            ->get();
            
        $this->info('Found ' . $jobs->count() . ' jobs without url_key');
        
        $updated = 0;
        foreach ($jobs as $job) {
            // Extract job title (first part before ' - ')
            $jobTitle = explode(' - ', $job->name)[0] ?? $job->name;
            
            // Generate slug
            $baseSlug = Str::slug($jobTitle);
            $slug = $baseSlug;
            
            // Ensure unique slug
            $counter = 1;
            while (DB::table('product_flat')
                    ->where('url_key', $slug)
                    ->where('id', '!=', $job->flat_id)
                    ->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            // Update the url_key
            DB::table('product_flat')
                ->where('id', $job->flat_id)
                ->update(['url_key' => $slug]);
                
            $this->line('Updated job "' . $jobTitle . '" with slug: ' . $slug);
            $updated++;
        }
        
        $this->info('Successfully updated ' . $updated . ' job url_keys');
        
        return Command::SUCCESS;
    }
}
