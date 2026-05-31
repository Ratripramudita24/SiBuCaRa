<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Plant;
use App\Models\PlantActivity;
use App\Policies\PlantPolicy;
use App\Policies\PlantActivityPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register policies
        Gate::policy(Plant::class, PlantPolicy::class);
        Gate::policy(PlantActivity::class, PlantActivityPolicy::class);
    }
}
