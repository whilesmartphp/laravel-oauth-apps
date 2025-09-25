<?php

namespace Whilesmart\LaravelOauthApps\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class ApiController extends BaseController
{
    /**
     * Return a success response.
     *
     * @param  mixed  $data
     */
    protected function success($data = null, ?string $message = null, int $statusCode = 200): JsonResponse
    {
        if (is_null($message)) {
            $message = __('oauth-apps.operation_successful');
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Return a failure response.
     */
    protected function failure(?string $message = null, int $statusCode = 400, array $errors = []): JsonResponse
    {
        if (is_null($message)) {
            $message = __('oauth-apps.operation_failed');
        }

        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }
}
