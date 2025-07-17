<?php

namespace Whilesmart\LaravelOauthApps\Transformers;

use Laravel\Passport\Client as BaseClient;
use League\Fractal\TransformerAbstract;

class AppTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include.
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include.
     */
    protected array $availableIncludes = [
        'team',
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(BaseClient $app)
    {
        return [
            'id' => $app->id,
            'secret' => $app->secret,
            'name' => $app->name,
            'description' => $app->description,
            'slug' => $app->slug,
            'team_id' => $app->user_id,
            'redirect' => $app->redirect,
            'personal_access_client' => $app->personal_access_client,
            'password_client' => $app->password_client,
            'revoked' => $app->revoked,
            'updated_at' => $app->updated_at,
            'created_at' => $app->created_at,
        ];
    }
}
