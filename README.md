# WhileSmart Laravel App Authentication Package

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
   $ composer require whilesmart/laravel-app-authentication
   ```

This package uses Laravel/passport. Please run the command below if you do not yet have passport configured

```bash
$ php artisan install:api --passport
```

### 2. Publish the configuration and migrations:

You do not need to publish the migrations and configurations except if you want to make modifications. You can choose to
publish
the migrations, routes, controllers separately or all at once.

#### 2.1 Publishing only the routes

Run the command below to publish only the routes.

```bash
$ php artisan vendor:publish --tag=app-authentication-routes
$ php artisan migrate
```

The routes will be available at `routes/app-authentication.php`. You should `require` this file in your `api.php` file.

```php
require 'app-authentication.php';
```

#### 2.2 Publishing only the migrations

+If you would like to make changes to the migration files, run the command below to publish only the migrations.

```bash
$ php artisan vendor:publish --tag=app-authentication-migrations
$ php artisan migrate
```

The migrations will be available in the `database/migrations` folder.

#### 2.3 Publish only the controllers

To publish the controllers, run the command below

```bash
$ php artisan vendor:publish --tag=app-authentication-controllers
$ php artisan migrate
```

The controllers will be available in the `app/Http/Controllers/Api/Auth` directory.
Finally, change the namespace in the published controllers to your namespace.

#### Note: Publishing the controllers will also publish the routes. See section 2.1

#### 2.4 Publish everything

To publish the migrations, routes and controllers, you can run the command below

```bash
$ php artisan vendor:publish --tag=app-authentication
$ php artisan migrate
```

#### Note: See section 2.1 above to make the routes accessible

3. **Optional: OpenAPI Documentation:**

    * Install and configure an OpenAPI package (e.g., `darkaonline/l5-swagger`).
    * Add necessary annotations to your controllers.

## Configuration

* The configuration file `config/app-authentication.php` allows you to customize various settings

## Usage

### API Endpoints

After installation, the following API endpoints will be available:

* **Application Management:**
    * `POST /api/apps`: Create a new application.
    * `GET /api/apps`: List user's applications.
    * `DELETE /api/apps/{app}`: Delete an application.
* **API Key Management:**
    * `POST /api/apps/{app}/api-keys`: Generate a new API key.
    * `DELETE /api/apps/{app}/api-keys/{apiKey}`: Revoke an API key.

### Example API Key Generation Request

POST /api/apps/{app}/api-keys

(Where `{app}` is the id of the app)

### API Key Authentication

To protect your API routes, use the `AuthenticateApiKey` middleware. Applications authenticate using the API Keys.

```php
// routes/api.php
Route::middleware('auth.api.key')->group(function () {
    // Your protected routes here
});
```

To use the API, provide the API key generated for the application in the X-YOURAPP-API-KEY header.

Please feel free to contribute by submitting pull requests or reporting issues.

### License

This package is open-source software licensed under the [MIT license](LICENSE.md)
