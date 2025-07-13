<?php

namespace Whilesmart\LaravelOauthApps\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Laravel\Passport\ClientRepository;
use Whilesmart\LaravelOauthApps\Http\Responses\Helper;
use Whilesmart\LaravelOauthApps\Models\App;
use Whilesmart\LaravelOauthApps\Transformers\AppTransformer;

class AppController extends Controller
{
    use Helper;

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
     *
     * @return array|Response
     */
    public function index(Request $request)
    {
        if (isset($request->with)) {
            Validator::make(
                [
                    'with' => $request->with,
                ],
                array_filter([
                    'with' => ['sometimes', 'array', Rule::in((new AppTransformer)->getAvailableIncludes())],
                ])
            )->validate();
        }
        $perPage = $request->query('perPage', 10);
        $user = $request->user();

        $paginator = App::where('user_id', $user->id)->paginate($perPage);

        return $this->paginator($paginator, new AppTransformer, $request->with ?? []);
    }

    /**
     * Store a newly created resource in storage.
     *
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'sometimes|string',
        ]);

        $user = $request->user();
        $client = $this->clientRepository->createAuthorizationCodeGrantClient($request->name, [], true, $user);
        $client->description = $request->description;
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;

        while (App::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter++;
        }

        $client->slug = $slug;

        $client->save();

        return $this->item($client->fresh(), new AppTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  $app
     * @return Response
     */
    public function show(Request $request, string $slug)
    {
        if (isset($request->with)) {
            Validator::make(
                [
                    'with' => $request->with,
                ],
                array_filter([
                    'with' => ['sometimes', 'array', Rule::in((new AppTransformer)->getAvailableIncludes())],
                ])
            )->validate();
        }
        $app = App::with($request->with ?? [])->whereSlug($slug)->firstOrFail();

        return $this->item(
            $app,
            new AppTransformer
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, string $slug)
    {
        $app = App::whereSlug($slug)->firstOrFail();
        if ($app->revoked) {
            $this->errorNotFound();
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'sometimes|string',
        ]);

        $app->forceFill([
            'name' => $request->name,
            'description' => $request->description,
            'redirect' => '',
        ])->save();

        return $this->item($app, new AppTransformer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, string $slug)
    {
        $app = App::whereSlug($slug)->firstOrFail();
        $app->delete();

        return $this->noContent();
    }

    public function generateApiKeys(Request $request, string $slug)
    {
        $app = App::whereSlug($slug)->firstOrFail();

        return $this->created(null, $app->tokens()->create([
            'id' => Str::uuid(), // Ensure the id is set
            'revoked' => false,
        ]));
    }

    public function getApiKeys(Request $request, string $slug)
    {
        $app = App::whereSlug($slug)->firstOrFail();

        return $this->success(null, $app->tokens);
    }

    public function deleteApiKey(Request $request, string $slug, $key)
    {
        $app = App::whereSlug($slug)->firstOrFail();
        $key = $app->tokens()->findOrFail($key);
        $key->delete();

        return $this->noContent();
    }
}
