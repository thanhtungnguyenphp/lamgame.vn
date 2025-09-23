<?php

namespace App\Console\Commands;

use App\Mail\WelcomeMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Illuminate\Support\Facades\Hash;

class TestRegistrationEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:registration-email {email=test@yopmail.com}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test complete registration flow with email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        try {
            $this->info('Testing registration flow with email: ' . $email);
            
            // Delete existing user if exists
            $existingUser = Customer::where('email', $email)->first();
            if ($existingUser) {
                $this->info('Deleting existing user...');
                $existingUser->delete();
            }
            
            // Get customer group repository
            $customerGroupRepo = app(CustomerGroupRepository::class);
            $defaultGroup = $customerGroupRepo->findOneWhere(['code' => 'general']);
            
            // Create new customer
            $customer = Customer::create([
                'first_name' => 'Test',
                'last_name' => 'User',
                'email' => $email,
                'password' => Hash::make('password123'),
                'channel_id' => core()->getCurrentChannel()->id,
                'customer_group_id' => $defaultGroup ? $defaultGroup->id : 2,
                'is_verified' => 1,
                'status' => 1,
            ]);
            
            $this->info('Customer created successfully with ID: ' . $customer->id);
            
            // Send welcome email
            $this->info('Sending welcome email...');
            Mail::to($customer->email)->send(new WelcomeMail($customer));
            
            $this->info('Welcome email sent successfully!');
            $this->info('Check email at: https://yopmail.com/en/wm');
            $this->info('Email address: ' . $email);
            
        } catch (\Exception $e) {
            $this->error('Registration test failed: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
