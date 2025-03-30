<?php

namespace Whilesmart\LaravelAppAuthentication\Models;

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
                'onUpdate' => true, // Ensure this key is included
                'separator' => '-', // Ensure this key is included
                'method' => null, // Ensure this key is included
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
