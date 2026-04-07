<?php

namespace Whilesmart\Customers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CustomersServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/customers.php', 'customers');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/customers.php' => config_path('customers.php'),
        ], 'customers-config');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'customers-migrations');

        if (config('customers.register_routes', true)) {
            Route::middleware(config('customers.route_middleware', ['api', 'auth:sanctum']))
                ->prefix(config('customers.route_prefix', 'api'))
                ->group(__DIR__.'/../routes/api.php');
        }
    }
}
