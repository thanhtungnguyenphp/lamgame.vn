<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupLegacyUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:cleanup-legacy-users {--force : Force cleanup without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup legacy users table data after migration to admin model';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧹 Cleaning up legacy users table data...');
        $this->newLine();

        try {
            // Check users table data
            $userCount = DB::table('users')->count();
            $tokenCount = DB::table('personal_access_tokens')
                ->where('tokenable_type', 'App\\Models\\User')
                ->count();
            
            $this->info("Found {$userCount} records in users table");
            $this->info("Found {$tokenCount} legacy API tokens");
            
            if ($userCount === 0 && $tokenCount === 0) {
                $this->info('✅ No legacy data found to clean up.');
                return 0;
            }

            if (!$this->option('force') && !$this->confirm('Do you want to proceed with cleanup?')) {
                $this->comment('Cleanup cancelled.');
                return 0;
            }

            $this->newLine();
            $deletedUsers = 0;
            $deletedTokens = 0;

            // Clean up personal access tokens first
            if ($tokenCount > 0) {
                $deletedTokens = DB::table('personal_access_tokens')
                    ->where('tokenable_type', 'App\\Models\\User')
                    ->delete();
                $this->info("🗑️  Deleted {$deletedTokens} legacy API tokens");
            }

            // Clean up users table data
            if ($userCount > 0) {
                $deletedUsers = DB::table('users')->delete();
                $this->info("🗑️  Deleted {$deletedUsers} user records");
            }

            $this->newLine();
            $this->info('📊 Cleanup Summary:');
            $this->table(
                ['Item', 'Deleted'],
                [
                    ['User Records', $deletedUsers],
                    ['API Tokens', $deletedTokens],
                    ['Total Cleaned', $deletedUsers + $deletedTokens]
                ]
            );

            $this->newLine();
            $this->info('✅ Legacy data cleanup completed successfully!');
            $this->comment('💡 System now uses Admin model exclusively for authentication.');
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Error during cleanup:');
            $this->error($e->getMessage());
            
            if ($this->option('verbose')) {
                $this->error($e->getTraceAsString());
            }
            
            return 1;
        }
    }
}
