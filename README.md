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

1.  **Require the package via Composer:**

    ```bash
    composer require whilesmart/laravel-app-authentication
    ```

2.  **Publish the configuration and migrations:**

    ```bash
    php artisan vendor:publish --provider="WhileSmart\LaravelAppAuthentication\Providers\AppAuthenticationServiceProvider"
    php artisan migrate
    ```

3.  **Optional: OpenAPI Documentation:**

    * Install and configure an OpenAPI package (e.g., `darkaonline/l5-swagger`).
    * Add necessary annotations to your controllers.

## Configuration

* The configuration file `config/app-authentication.php` allows you to customize various settings, including:
    * API key length.
    * API key expiration.

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
