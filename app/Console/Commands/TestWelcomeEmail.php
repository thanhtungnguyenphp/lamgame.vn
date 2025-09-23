<?php

namespace App\Console\Commands;

use App\Mail\WelcomeMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Webkul\Customer\Models\Customer;

class TestWelcomeEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:welcome-email {email=test@example.com}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test welcome email functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        // Create a test customer object
        $customer = new Customer([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => $email
        ]);

        try {
            Mail::to($email)->send(new WelcomeMail($customer));
            $this->info('Welcome email sent successfully to: ' . $email);
            $this->info('Check Mailpit at: http://localhost:8028');
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
