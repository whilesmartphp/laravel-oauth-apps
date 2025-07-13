<?php

namespace Whilesmart\LaravelOauthApps\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        if (! $request->header('X-client-id')) {
            throw new NoClientIdException;
        }
        if (! $request->header('X-secret-id')) {
            throw new NoSecretIdException;
        }

        $app = App::where('id', $request->header('X-client-id'))
            ->where('secret', $request->header('X-secret-id'))->first();
        if (! $app) {
            throw new InvalidAppCredentialsException;
        }

        return $next($request);
    }
}
