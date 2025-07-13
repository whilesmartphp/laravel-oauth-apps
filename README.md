# WhileSmart Laravel Oauth Apps Package

A Laravel package providing out-of-the-box authentication for applications and API key management.

## Features

* **Application Management:**
    * Users (managed by an external system) can register and manage their applications.
    * Each application gets a unique ID.
* **API Key Generation and Revocation:**
    * Users can generate API keys for their applications.
    * API keys have configurable expiration dates.
    * Ability to revoke API keys.
* **API Key Authentication:**
    * Middleware to protect API routes using API keys.
* **OpenAPI Documentation:**
    * Easily generate API documentation using packages like `l5-swagger`.
* **Configurable Settings:**
    * Easily customize settings via a configuration file.

## Installation

### 1. Require the package

   ```bash
   $ composer require whilesmart/laravel-oauth-apps
   ```

This package uses Laravel/passport. Please run the command below if you do not yet have passport configured

```bash
$ php artisan install:api --passport
```

Additionally, this command will ask if you would like to use UUIDs as the primary key value of the Passport Client model
instead of auto-incrementing integer
. Select UUID

Or simply run

```bash
$ php artisan passport:install --uuids
```

### 2. Publish the configuration and migrations:

You do not need to publish the migrations and configurations except if you want to make modifications. You can choose to
publish
the migrations, routes, controllers separately or all at once.

#### 2.1 Publishing only the routes

Run the command below to publish only the routes.

```bash
$ php artisan vendor:publish --tag=laravel-oauth-apps-routes
$ php artisan migrate
```

 The routes will be available at `routes/laravel-oauth-apps.php`. If `register_routes` in `config/laravel-oauth-apps.php`
is `true` (default), the routes will be automatically registered with the defined `route_prefix` (default `api`). If you
wish to disable auto-registration and manually control the route definition, set `register_routes` to `false` in your
config and then `require 'laravel-oauth-apps.php';` in your `api.php` file.

```php
require 'laravel-oauth-apps.php';
```

#### 2.2 Publishing only the migrations

+If you would like to make changes to the migration files, run the command below to publish only the migrations.

```bash
$ php artisan vendor:publish --tag=laravel-oauth-apps-migrations
$ php artisan migrate
```

The migrations will be available in the `database/migrations` folder.

#### 2.3 Publish only the controllers

To publish the controllers, run the command below

```bash
$ php artisan vendor:publish --tag=laravel-oauth-apps-controllers
$ php artisan migrate
```

The controllers will be available in the `app/Http/Controllers/Api/Auth` directory.
Finally, change the namespace in the published controllers to your namespace.

#### Note: Publishing the controllers will also publish the routes. See section 2.1

#### 2.4 Publish  the config

To publish the config, run the command below

```bash
php artisan vendor:publish --tag=laravel-app-authentication-config
```

The config file will be available in the `config/laravel-app-authentication.php`.
The config file has the folowing variables:

- `register_routes`: Default `true`. Auto registers the routes. If you do not want to auto-register the routes, set the
  value to `false
- `route_prefix`: Default `api`. Defines the prefix for the auto-registered routes.

#### 2.5 Publish everything

To publish the migrations, routes and controllers, you can run the command below

```bash
$ php artisan vendor:publish --tag=laravel-oauth-apps
$ php artisan migrate
```

#### Note: See section 2.1 above to make the routes accessible

3. **Optional: OpenAPI Documentation:**

    * Install and configure an OpenAPI package (e.g., `darkaonline/l5-swagger`).
    * Add necessary annotations to your controllers.

## Configuration

* The configuration file `config/laravel-oauth-apps.php` allows you to customize various settings

## Usage

### API Endpoints

After installation, the following API endpoints will be available:

* **Application Management:**
    * `POST /api/apps`: Create a new application.
    * `GET /api/apps`: List user's applications.
    * `DELETE /api/apps/{app}`: Delete an application.
* **API Key Management:**
    * `POST /apps/{app}/api-keys`: Generate a new API key.
    * `DELETE /apps/{app}/api-keys/{apiKey}`: Revoke an API key.

### Example API Key Generation Request

POST /apps/{app}/api-keys

(Where `{app}` is the id of the app)

### API Key Authentication

Add the `Whilesmart\LaravelOauthApps\Http\Middleware\EnforceHeaderAuth` middleware to your `Kernel.php` if
you are using Laravel <11

```php
    protected $routeMiddleware = [
        ...,
        'AuthenticateApiKey' => \Whilesmart\LaravelOauthApps\Http\Middleware\EnforceHeaderAuth::class,
    ];

```

or `bootstrap/app.php` if you are using Laravel 11+

```php
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
    web: __DIR__.'/../routes/web.php',
        // api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        // channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        ...
        $middleware->alias(['auth.api.key'=> \Whilesmart\LaravelOauthApps\Http\Middleware\EnforceHeaderAuth::class]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

To protect your API routes, use the `auth.api.key` middleware. Applications authenticate using the API Keys.

```php
// routes/api.php
Route::middleware('auth.api.key')->group(function () {
    // Your protected routes here
});
```

To use the API, provide the id generated for the application in the **X-client-id** header, and the secret in the *
*X-secret-id**

Please feel free to contribute by submitting pull requests or reporting issues.

### License

This package is open-source software licensed under the [MIT license](LICENSE.md)
