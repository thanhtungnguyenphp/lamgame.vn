<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncAdminUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:sync-users {--force : Force sync without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync admin users from admins table to users table for API access';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Syncing admin users to users table for API access...');
        $this->newLine();

        try {
            // Get all admins
            $admins = DB::table('admins')->get();
            
            if ($admins->isEmpty()) {
                $this->warn('No admin users found in admins table.');
                return 0;
            }

            $this->info("Found {$admins->count()} admin users.");
            $this->newLine();

            $synced = 0;
            $skipped = 0;
            $updated = 0;

            foreach ($admins as $admin) {
                $this->line("Processing: {$admin->email}");
                
                // Check if user already exists in users table
                $existingUser = User::where('email', $admin->email)->first();
                
                if ($existingUser) {
                    // Update existing user if admin data is newer or different
                    if ($admin->updated_at > $existingUser->updated_at || 
                        $admin->name !== $existingUser->name ||
                        $admin->status != $existingUser->status) {
                        
                        $existingUser->update([
                            'name' => $admin->name,
                            'password' => $admin->password,
                            'status' => $admin->status,
                            'updated_at' => now()
                        ]);
                        
                        $this->info("  ✅ Updated user: {$admin->email}");
                        $updated++;
                    } else {
                        $this->comment("  ⏭️  Skipped (already exists): {$admin->email}");
                        $skipped++;
                    }
                } else {
                    // Create new user
                    User::create([
                        'name' => $admin->name,
                        'email' => $admin->email,
                        'password' => $admin->password, // Use same hash
                        'status' => $admin->status ?? true,
                        'created_at' => $admin->created_at ?? now(),
                        'updated_at' => now(),
                    ]);
                    
                    $this->info("  ✅ Created user: {$admin->email}");
                    $synced++;
                }
            }

            $this->newLine();
            $this->info('📊 Sync Summary:');
            $this->table(
                ['Action', 'Count'],
                [
                    ['Created', $synced],
                    ['Updated', $updated], 
                    ['Skipped', $skipped],
                    ['Total Processed', $admins->count()]
                ]
            );

            $this->newLine();
            $this->info('✅ Admin users sync completed successfully!');
            $this->comment('💡 Admin users can now use API endpoints with their admin credentials.');
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Error syncing admin users:');
            $this->error($e->getMessage());
            
            if ($this->option('verbose')) {
                $this->error($e->getTraceAsString());
            }
            
            return 1;
        }
    }
}
