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
    protected function success($data = null, string $message = 'Operation successful', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Return a failure response.
     */
    protected function failure(string $message = 'Operation failed', int $statusCode = 400, array $errors = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }
}
