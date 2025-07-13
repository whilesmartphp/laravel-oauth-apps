<?php

namespace Whilesmart\LaravelOauthApps;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LaravelOauthAppsServiceProvider extends ServiceProvider
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

        if (config('laravel-oauth-apps.register_routes', true)) {
            $prefix = config('laravel-oauth-apps.route_prefix', 'api');
            if ($prefix) {
                Route::prefix($prefix)->group(function () {
                    $this->loadRoutesFrom(__DIR__.'/../routes/laravel-oauth-apps.php');
                });
            } else {
                $this->loadRoutesFrom(__DIR__.'/../routes/laravel-oauth-apps.php');
            }
        }

        $this->publishes([
            __DIR__.'/../routes/laravel-oauth-apps.php' => base_path('routes/laravel-oauth-apps.php'),
        ], ['laravel-oauth-apps', 'laravel-oauth-apps-routes', 'laravel-oauth-apps-controllers']);

        $this->publishes([
            __DIR__.'/Http/Controllers' => app_path('Http/Controllers/Api'),
        ], ['laravel-oauth-apps', 'laravel-oauth-apps-controllers']);

        $this->loadMigrationsFrom(__DIR__.'/../migrations');

        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], ['laravel-oauth-apps', 'laravel-oauth-apps-migrations']);

        // Publish middleware
        $this->publishes([
            __DIR__.'/Http/Middleware' => app_path('Http/Middleware'),
        ], ['laravel-oauth-apps', 'laravel-oauth-apps-middleware']);

        // Publish config
        $this->publishes([
            __DIR__.'/../config/laravel-oauth-apps.php' => config_path('laravel-oauth-apps.php'),
        ], ['laravel-oauth-apps', 'laravel-oauth-apps-config']);
    }
}
