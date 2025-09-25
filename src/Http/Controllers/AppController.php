<?php

namespace Whilesmart\LaravelOauthApps\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Passport\ClientRepository;
use Whilesmart\LaravelOauthApps\Interfaces\IAppControllerInterface;
use Whilesmart\LaravelOauthApps\Models\App;

class AppController extends ApiController implements IAppControllerInterface
{
    private ClientRepository $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('perPage', 10);
        $user = $request->user();

        $apps = App::where('user_id', $user->id)->paginate($perPage);

        return $this->success($apps);
    }

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

    public function show(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();
        $app = App::where('user_id', $user->id)->whereSlug($slug)->firstOrFail();

        return $this->success($app);
    }

    public function update(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();
        $app = App::where('user_id', $user->id)->whereSlug($slug)->firstOrFail();
        if ($app->revoked) {
            $this->failure(__('oauth-apps.revoked', ['slug' => $slug]), 403);
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

    public function destroy(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();
        $app = App::where('user_id', $user->id)->whereSlug($slug)->firstOrFail();
        $app->delete();

        return $this->success(null, __('oauth-apps.deleted'), 204);
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

        return $this->success($newSecret, __('oauth-apps.secret_regenerated'));
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

        return $this->success(null, __('oauth-apps.api_key_deleted'), 204);
    }
}
