<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('admins')->whereNotNull('api_token')->cursor()->each(function ($admin) {
            DB::table('admins')->where('id', $admin->id)->update([
                'api_token' => hash('sha256', $admin->api_token),
            ]);
        });
    }

    public function down(): void
    {
        // Cannot reverse hash — tokens must be regenerated manually
    }
};
