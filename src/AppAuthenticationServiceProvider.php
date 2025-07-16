<?php

namespace Whilesmart\LaravelAppAuthentication;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppAuthenticationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        if (config('laravel-app-authentication.register_routes', true)) {
            $prefix = config('laravel-app-authentication.route_prefix', 'api');
            if ($prefix) {
                Route::prefix($prefix)->group(function () {
                    $this->loadRoutesFrom(__DIR__.'/../routes/laravel-app-authentication.php');
                });
            } else {
                $this->loadRoutesFrom(__DIR__.'/../routes/laravel-app-authentication.php');
            }
        }

        $this->publishes([
            __DIR__.'/../routes/laravel-app-authentication.php' => base_path('routes/laravel-app-authentication.php'),
        ], ['laravel-app-authentication', 'laravel-app-authentication-routes', 'laravel-app-authentication-controllers']);

        $this->publishes([
            __DIR__.'/Http/Controllers' => app_path('Http/Controllers/Api'),
        ], ['laravel-app-authentication', 'laravel-app-authentication-controllers']);

        $this->loadMigrationsFrom(__DIR__.'/../migrations');

        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], ['laravel-app-authentication', 'laravel-app-authentication-migrations']);

        // Publish middleware
        $this->publishes([
            __DIR__.'/Http/Middleware' => app_path('Http/Middleware'),
        ], ['laravel-app-authentication', 'laravel-app-authentication-middleware']);

        // Publish config
        $this->publishes([
            __DIR__.'/../config/laravel-app-authentication.php' => config_path('laravel-app-authentication.php'),
        ], ['laravel-app-authentication', 'laravel-app-authentication-config']);
    }
}
