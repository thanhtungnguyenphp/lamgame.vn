<?php

namespace Webkul\JobManagement\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        //
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        parent::boot();

        $this->app->register(JobManagementServiceProvider::class);
    }
}
