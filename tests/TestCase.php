<?php

use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use Orchestra\Testbench\Attributes\WithMigration;
use Workbench\App\Models\User;

use function Orchestra\Testbench\workbench_path;

#[WithMigration]
class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    //    protected $enablesPackageDiscoveries = true;

    public function test_api_user_can_create_an_app()
    {
        $user = $this->create_test_user();

        $faker = Factory::create();
        $response = $this->actingAs($user)->postJson('/api/apps', [
            'name' => $faker->unique()->name,
            'description' => $faker->text,
        ]);

        $response->assertStatus(200);

    }

    private function create_test_user()
    {
        return User::create([
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);
    }

    public function test_api_user_can_get_their_apps()
    {
        $user = $this->create_test_user();

        $appName = fake()->unique()->name;

        $faker = Factory::create();
        $response = $this->actingAs($user)->postJson('/api/apps', [
            'name' => $appName,
            'description' => $faker->text,
        ]);

        $response->assertStatus(200);

        $response = $this->actingAs($user)->getJson('/api/apps');
        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertEquals($appName, $data[0]['name']);
    }

    public function test_api_user_can_get_a_single_app()
    {
        $user = $this->create_test_user();

        $appName = fake()->unique()->name;

        $faker = Factory::create();
        $response = $this->actingAs($user)->postJson('/api/apps', [
            'name' => $appName,
            'description' => $faker->text,
        ]);
        $createdApp = $response->json('data');

        $response->assertStatus(200);

        $response = $this->actingAs($user)->getJson("/api/apps/{$createdApp['slug']}");
        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertEquals($appName, $data['name']);
    }

    public function test_api_user_can_update_an_app()
    {
        $user = $this->create_test_user();

        $appName = fake()->unique()->name;

        $faker = Factory::create();
        $response = $this->actingAs($user)->postJson('/api/apps', [
            'name' => $appName,
            'description' => $faker->text,
        ]);
        $createdApp = $response->json('data');

        $response->assertStatus(200);

        $newName = fake()->unique()->name;
        $newDescription = 'new description';

        $response = $this->actingAs($user)->putJson("/api/apps/{$createdApp['slug']}", ['name' => $newName, 'description' => $newDescription]);
        $response->assertStatus(200);

        $updatedApp = $response->json('data');
        $response = $this->actingAs($user)->getJson("/api/apps/{$updatedApp['slug']}");
        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertEquals($newName, $data['name']);
        $this->assertEquals($newDescription, $data['description']);
    }

    public function test_api_user_can_generate_keys_for_an_app()
    {
        $user = $this->create_test_user();

        $appName = fake()->unique()->name;

        $faker = Factory::create();
        $response = $this->actingAs($user)->postJson('/api/apps', [
            'name' => $appName,
            'description' => $faker->text,
        ]);
        $createdApp = $response->json('data');

        $response->assertStatus(200);

        $response = $this->actingAs($user)->postJson("/api/apps/{$createdApp['slug']}/api-keys");
        $response->assertStatus(201);
    }

    public function test_api_user_can_get_generated_tokens_for_an_app()
    {
        $user = $this->create_test_user();

        $appName = fake()->unique()->name;

        $faker = Factory::create();
        $response = $this->actingAs($user)->postJson('/api/apps', [
            'name' => $appName,
            'description' => $faker->text,
        ]);
        $createdApp = $response->json('data');

        $response->assertStatus(200);

        $response = $this->actingAs($user)->postJson("/api/apps/{$createdApp['slug']}/api-keys");
        $response->assertStatus(201);

        $response = $this->actingAs($user)->getJson("/api/apps/{$createdApp['slug']}/api-keys");
        $response->assertStatus(200);
    }

    public function test_api_user_can_delete_generated_tokens_for_an_app()
    {
        $user = $this->create_test_user();

        $appName = fake()->unique()->name;

        $faker = Factory::create();
        $response = $this->actingAs($user)->postJson('/api/apps', [
            'name' => $appName,
            'description' => $faker->text,
        ]);
        $createdApp = $response->json('data');

        $response->assertStatus(200);

        $response = $this->actingAs($user)->postJson("/api/apps/{$createdApp['slug']}/api-keys");
        $response->assertStatus(201);

        $key = $response->json();
        $response = $this->actingAs($user)->deleteJson("/api/apps/{$createdApp['slug']}/api-keys/{$key['id']}");
        $response->assertStatus(204);
    }

    public function test_api_user_can_delete_an_app()
    {
        $user = $this->create_test_user();

        $appName = fake()->unique()->name;

        $faker = Factory::create();
        $response = $this->actingAs($user)->postJson('/api/apps', [
            'name' => $appName,
            'description' => $faker->text,
        ]);
        $response->assertStatus(200);

        $createdApp = $response->json('data');

        $response = $this->actingAs($user)->delete("/api/apps/{$createdApp['slug']}");
        $response->assertStatus(204);
    }

    /**
     * Define database migrations.
     */
    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(
            workbench_path('database/migrations')
        );
    }

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        Passport::$clientUuids = true;

    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app)
    {
        return [
            'Whilesmart\LaravelAppAuthentication\AppAuthenticationServiceProvider',
        ];
    }
}
