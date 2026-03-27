<?php

namespace App\Observers;

use Webkul\User\Models\Admin;

class AdminObserver
{
    public function creating(Admin $admin): void
    {
        if ($admin->api_token && strlen($admin->api_token) !== 64) {
            $admin->api_token = hash('sha256', $admin->api_token);
        }
    }
}
