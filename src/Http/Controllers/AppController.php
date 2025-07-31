<?php

namespace Whilesmart\LaravelOauthApps\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Passport\ClientRepository;
use Whilesmart\LaravelOauthApps\Models\App;

class AppController extends ApiController
{
    private ClientRepository $clientRepository;

    /**
     * AppController constructor.
     */
    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('perPage', 10);
        $user = $request->user();

        $apps = App::where('user_id', $user->id)->paginate($perPage);

        return $this->success($apps);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'sometimes|string',
        ]);

        $user = $request->user();
        $client = $this->clientRepository->createAuthorizationCodeGrantClient($request->name, [], true, $user);
        $secret = $client->plainSecret;
        $client->description = $request->description;
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;

        while (App::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter++;
        }

        $client->slug = $slug;

        $client->save();

        return $this->success(['client' => $client->refresh(), 'secret' => $secret]);
    }

    /**
     * Display the specified resource.
     *
     * @param  $app
     */
    public function show(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();
        $app = App::where('user_id', $user->id)->whereSlug($slug)->firstOrFail();

        return $this->success($app);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();
        $app = App::where('user_id', $user->id)->whereSlug($slug)->firstOrFail();
        if ($app->revoked) {
            $this->failure("App [$slug] has been revoked.", 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'sometimes|string',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'redirect' => '',
        ];

        $app->forceFill($data)->save();

        return $this->success($app);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();
        $app = App::where('user_id', $user->id)->whereSlug($slug)->firstOrFail();
        $app->delete();

        return $this->success(null, 'App has been deleted', 204);
    }

    public function generateApiKeys(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();
        $app = App::where('user_id', $user->id)->whereSlug($slug)->firstOrFail();

        return $this->success($app->tokens()->create([
            'id' => Str::uuid(), // Ensure the id is set
            'revoked' => false,
        ]), statusCode: 201);
    }

    public function regenerateSecret(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();
        $app = App::where('user_id', $user->id)->whereSlug($slug)->firstOrFail();
        $newSecret = Str::random(40);
        $app->forceFill(['secret' => Hash::make($newSecret)])->save();

        return $this->success($newSecret, 'Secret regenerated successfully');
    }

    public function getApiKeys(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();
        $app = App::where('user_id', $user->id)->whereSlug($slug)->firstOrFail();

        return $this->success($app->tokens);
    }

    public function deleteApiKey(Request $request, string $slug, $key): JsonResponse
    {
        $user = $request->user();
        $app = App::where('user_id', $user->id)->whereSlug($slug)->firstOrFail();
        $key = $app->tokens()->findOrFail($key);
        $key->delete();

        return $this->success(null, 'API key has been deleted', 204);
    }
}
