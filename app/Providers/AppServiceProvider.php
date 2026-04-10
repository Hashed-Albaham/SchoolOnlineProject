<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\FinancialService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix for MySQL key length issue
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);

        // Enforce strict mode in non-production environments
        // This prevents N+1 queries, unfillable property assignment, and accessing missing attributes
        \Illuminate\Database\Eloquent\Model::shouldBeStrict(!app()->isProduction());
    }
}
