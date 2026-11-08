<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\OrdenProduccionModel;
use App\Observers\OrdenProduccionObserver;

class ProductionAutomationServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
       
    }

    
    public function boot(): void
    {

        OrdenProduccionModel::observe(OrdenProduccionObserver::class);
    }
}
