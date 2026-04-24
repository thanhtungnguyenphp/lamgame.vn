<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Clean up job data from products table after migration to job_postings.
 */
return new class extends Migration
{
    public function up(): void
    {
        $jobProductIds = DB::table('products')->where('type', 'job')->pluck('id');

        if ($jobProductIds->isEmpty()) return;

        // Delete related data
        DB::table('product_attribute_values')->whereIn('product_id', $jobProductIds)->delete();
        DB::table('product_flat')->whereIn('product_id', $jobProductIds)->delete();
        DB::table('product_categories')->whereIn('product_id', $jobProductIds)->delete();
        DB::table('product_channels')->whereIn('product_id', $jobProductIds)->delete();
        DB::table('product_images')->whereIn('product_id', $jobProductIds)->delete();
        DB::table('product_inventories')->whereIn('product_id', $jobProductIds)->delete();
        DB::table('job_skills')->whereIn('product_id', $jobProductIds)->delete();
        DB::table('job_benefits')->whereIn('product_id', $jobProductIds)->delete();

        // Delete job products
        DB::table('products')->whereIn('id', $jobProductIds)->delete();
    }

    public function down(): void
    {
        // Cannot reverse - data migration handles recreation
    }
};
