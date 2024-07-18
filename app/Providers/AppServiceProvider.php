<?php

namespace App\Providers;

use App\Services\Checkout\CheckoutService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(CheckoutService::class, function ($app) {
            $pricingRules = config('pricing_rules.rules');
            return new CheckoutService($pricingRules);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
