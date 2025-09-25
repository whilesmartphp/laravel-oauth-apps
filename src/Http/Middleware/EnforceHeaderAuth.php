<?php

namespace Whilesmart\LaravelOauthApps\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Whilesmart\LaravelOauthApps\Models\App;

class EnforceHeaderAuth
{
    private const CLIENT_ID_HEADER = 'X-Client-Id';

    private const SECRET_ID_HEADER = 'X-Client-Secret';

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->header(self::CLIENT_ID_HEADER)) {
            return $this->unauthorizedResponse('No client id provided', 'MISSING_CLIENT_ID');
        }
        if (! $request->header(self::SECRET_ID_HEADER)) {
            return $this->unauthorizedResponse('No secret Id provided', 'MISSING_SECRET_ID');
        }

        $app = App::where('id', $request->header(self::CLIENT_ID_HEADER))->first();
        if (! $app) {
            return $this->unauthorizedResponse('Invalid x-client-id and or x-secret-id provided.', 'INVALID_CREDENTIALS');
        }

        $hashed_secret = $app->secret;
        if (! Hash::check($request->header(self::SECRET_ID_HEADER), $hashed_secret)) {
            return $this->unauthorizedResponse('Invalid x-client-id and or x-secret-id provided.', 'INVALID_CREDENTIALS');
        }

        $request->merge(['app' => $app]);

        return $next($request);
    }

    private function unauthorizedResponse(string $message, string $code)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'code' => $code,
            'data' => [],
        ], 401);
    }
}
