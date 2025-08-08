<?php

namespace Whilesmart\LaravelOauthApps\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Whilesmart\LaravelOauthApps\Exceptions\InvalidAppCredentialsException;
use Whilesmart\LaravelOauthApps\Exceptions\NoClientIdException;
use Whilesmart\LaravelOauthApps\Exceptions\NoSecretIdException;
use Whilesmart\LaravelOauthApps\Models\App;

class EnforceHeaderAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     *
     * @throws InvalidAppCredentialsException
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->header('X-Client-Id')) {
            throw new NoClientIdException;
        }
        if (! $request->header('X-Client-Secret')) {
            throw new NoSecretIdException;
        }

        $app = App::where('id', $request->header('X-Client-Id'))->first();
        if (! $app) {
            throw new InvalidAppCredentialsException;
        }

        $hashed_secret = $app->secret;
        if (! Hash::check($request->header('X-Client-Secret'), $hashed_secret)) {
            throw new InvalidAppCredentialsException;
        }

        $request->app = $app;

        return $next($request);
    }
}
