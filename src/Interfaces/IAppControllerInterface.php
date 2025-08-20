<?php

namespace Whilesmart\LaravelOauthApps\Interfaces;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'App',
    properties: [
        new OA\Property(property: 'id', description: 'ID of the app', type: 'string', format: 'uuid'),
        new OA\Property(property: 'name', description: 'Name of the app', type: 'string'),
        new OA\Property(property: 'description', description: 'Description of the app', type: 'string'),
        new OA\Property(property: 'slug', description: 'App slug', type: 'string'),
        new OA\Property(property: 'created_at', description: 'Date created', type: 'datetime'),
        new OA\Property(property: 'updated_at', description: 'Date updated', type: 'datetime'),
        new OA\Property(property: 'user_id', type: 'integer'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'ErrorResponse',
    properties: [
        new OA\Property(
            property: 'message',
            type: 'string'
        ),
        new OA\Property(
            property: 'errors',
            type: 'array',
            items: new OA\Items(type: 'object', additionalProperties: true)
        ),
    ],
    type: 'object'
)]
interface IAppControllerInterface
{
    #[OA\Get(
        path: '/api/apps',
        summary: 'Get apps',
        security: [
            ['sanctum' => []],
        ],
        tags: ['App'],
        parameters: [
            new OA\Parameter(
                name: 'per_page',
                description: 'Number of items per page',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 20, minimum: 1)
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Apps loaded',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', properties: [
                            new OA\Property(property: 'current_page', description: 'Current page', type: 'integer', example: 1),
                            new OA\Property(property: 'last_page', description: 'Last page', type: 'integer', example: 1),
                            new OA\Property(property: 'per_page', description: 'Per page', type: 'integer', example: 1),
                            new OA\Property(property: 'total', description: 'Total items', type: 'integer', example: 1),
                            new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/App')),
                        ], type: 'object'),
                    ]
                )),
            new OA\Response(response: 400, description: 'Bad Request',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 401, description: 'Not Authenticated',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 403, description: 'Unauthorized',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 500, description: 'Server error',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
        ]
    )]
    public function index(Request $request): JsonResponse;

    #[OA\Post(
        path: '/api/apps',
        summary: 'Create an app',
        security: [
            ['sanctum' => []],
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(

                properties: [
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'description', type: 'string'),
                ]
            )
        ),
        tags: ['App'],
        responses: [
            new OA\Response(response: 201, description: 'App created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            properties: [
                                new OA\Property(property: 'client', ref: '#/components/schemas/App'),
                                new OA\Property(property: 'secret', type: 'string'),
                            ],
                            type: 'object',
                        ),
                    ],
                    type: 'object'
                )),
            new OA\Response(response: 400, description: 'Bad Request',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 401, description: 'Not Authenticated',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 403, description: 'Unauthorized',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 500, description: 'Server error',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
        ]
    )]
    public function store(Request $request): JsonResponse;

    #[OA\Get(
        path: '/api/apps/{app_slug}',
        summary: 'Get a single app',
        security: [
            ['sanctum' => []],
        ],
        tags: ['App'],
        parameters: [
            new OA\Parameter(
                name: 'app_slug',
                description: 'App slug',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'App loaded',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/App'),
                    ]
                )),
            new OA\Response(response: 400, description: 'Bad Request',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 401, description: 'Not Authenticated',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 403, description: 'Unauthorized',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 404, description: 'Not Found',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 500, description: 'Server error',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
        ]
    )]
    public function show(Request $request, string $slug): JsonResponse;

    #[OA\Put(
        path: '/api/apps/{app_slug}',
        summary: 'Update an app',
        security: [
            ['sanctum' => []],
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(

                properties: [
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'description', type: 'string'),
                ]
            )
        ),
        tags: ['App'],

        parameters: [
            new OA\Parameter(
                name: 'app_slug',
                description: 'App slug',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'App updated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            ref: '#/components/schemas/App',
                            type: 'object',
                        ),
                    ],
                    type: 'object'
                )),
            new OA\Response(response: 400, description: 'Bad Request',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 401, description: 'Not Authenticated',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 403, description: 'Unauthorized',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 500, description: 'Server error',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
        ]
    )]
    public function update(Request $request, string $slug): JsonResponse;

    #[OA\Delete(
        path: '/api/apps/{app_slug}',
        summary: 'Delete an app',
        security: [
            ['sanctum' => []],
        ],
        tags: ['App'],

        parameters: [
            new OA\Parameter(
                name: 'app_slug',
                description: 'App slug',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'App deleted'),
            new OA\Response(response: 400, description: 'Bad Request',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 401, description: 'Not Authenticated',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 403, description: 'Unauthorized',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 500, description: 'Server error',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
        ]
    )]
    public function destroy(Request $request, string $slug): JsonResponse;

    #[OA\Post(
        path: '/api/apps/{app_slug}/api-keys',
        summary: 'Create an app key',
        security: [
            ['sanctum' => []],
        ],
        tags: ['App'],

        parameters: [
            new OA\Parameter(
                name: 'app_slug',
                description: 'App slug',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(response: 201, description: 'App key created', content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'data', properties: [
                        new OA\Property(property: 'id', type: 'string', format: 'uuid'),
                        new OA\Property(property: 'client_id', type: 'string', format: 'uuid'),
                    ], type: 'object'),
                ]
            )),
            new OA\Response(response: 400, description: 'Bad Request',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 401, description: 'Not Authenticated',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 403, description: 'Unauthorized',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 500, description: 'Server error',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
        ]
    )]
    public function generateApiKeys(Request $request, string $slug): JsonResponse;

    #[OA\Post(
        path: '/api/apps/{app_slug}/regenerate-secret',
        summary: 'Regenerate app secret',
        security: [
            ['sanctum' => []],
        ],
        tags: ['App'],
        parameters: [
            new OA\Parameter(
                name: 'app_slug',
                description: 'App slug',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Secret regenerated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'string'),
                    ]
                )),
            new OA\Response(response: 400, description: 'Bad Request',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 401, description: 'Not Authenticated',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 403, description: 'Unauthorized',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 404, description: 'Not Found',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 500, description: 'Server error',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
        ]
    )]
    public function regenerateSecret(Request $request, string $slug): JsonResponse;

    #[OA\Get(
        path: '/api/apps/{app_slug}/api-keys',
        summary: 'Get app keys',
        security: [
            ['sanctum' => []],
        ],
        tags: ['App'],

        parameters: [
            new OA\Parameter(
                name: 'app_slug',
                description: 'App slug',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Api keys loaded ',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'id', type: 'string', format: 'uuid'),
                                new OA\Property(property: 'client_id', type: 'string', format: 'uuid'),
                            ],
                            type: 'object'
                        )),
                    ]
                )),
            new OA\Response(response: 400, description: 'Bad Request',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 401, description: 'Not Authenticated',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 403, description: 'Unauthorized',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 500, description: 'Server error',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
        ]
    )]
    public function getApiKeys(Request $request, string $slug): JsonResponse;

    #[OA\Delete(
        path: '/api/apps/{app_slug}/api-keys/{key_id}',
        summary: 'Delete app key',
        security: [
            ['sanctum' => []],
        ],
        tags: ['App'],

        parameters: [
            new OA\Parameter(
                name: 'app_slug',
                description: 'App slug',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
            new OA\Parameter(
                name: 'key_id',
                description: 'API Key ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'App key deleted'),
            new OA\Response(response: 400, description: 'Bad Request',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 401, description: 'Not Authenticated',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 403, description: 'Unauthorized',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
            new OA\Response(response: 500, description: 'Server error',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ErrorResponse',
                    type: 'object'
                )),
        ]
    )]
    public function deleteApiKey(Request $request, string $slug, $key): JsonResponse;
}
