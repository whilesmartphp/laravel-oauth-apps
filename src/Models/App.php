<?php

namespace Whilesmart\LaravelOauthApps\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Laravel\Passport\Client as BaseClient;

class App extends BaseClient
{
    use Sluggable;

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => true,
                'separator' => '-',
                'method' => null,
                'unique' => true,
                'uniqueSuffix' => null,
                'firstUniqueSuffix' => 2,
                'includeTrashed' => false,
                'reserved' => null,
                'maxLength' => null,
                'maxLengthKeepWords' => true,
                'slugEngineOptions' => [],
            ],
        ];
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
