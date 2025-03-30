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

        Route::prefix('api')->group(function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/app-authentication.php');
        });

        $this->publishes([
            __DIR__.'/../routes/app-authentication.php' => base_path('routes/app-authentication.php'),
        ], ['app-authentication', 'app-authentication-routes', 'app-authentication-controllers']);

        $this->publishes([
            __DIR__.'/Http/Controllers' => app_path('Http/Controllers/Api'),
        ], ['app-authentication', 'app-authentication-controllers']);

        // Publish config
        $this->publishes([
            __DIR__.'/../config/app-authentication.php' => config_path('app-authentication.php'),
        ], ['app-authentication', 'app-authentication-controllers']);

        $this->loadMigrationsFrom(__DIR__.'/../migrations');

        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], ['app-authentication', 'app-authentication-migrations']);
    }
}
