<?php

use App\Enums\Gender;
use App\Models\Species;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

it('registers a user and returns bearer token', function (): void {
    $response = postJson('/api/v1/auth/register', [
        'name' => 'Forest User',
        'email' => 'forest@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'device_name' => 'pest-suite',
    ]);

    $response
        ->assertCreated()
        ->assertJsonPath('token_type', 'Bearer')
        ->assertJsonStructure([
            'access_token',
            'user' => ['id', 'name', 'email'],
        ]);
});

it('returns russian validation messages for invalid register payload', function (): void {
    postJson('/api/v1/auth/register', [
        'name' => '',
        'email' => 'not-an-email',
        'password' => 'short',
        'password_confirmation' => 'mismatch',
    ])
        ->assertUnprocessable()
        ->assertJsonPath('message', 'Ошибка валидации данных.')
        ->assertJsonPath('errors.name.0', 'Поле имя обязательно для заполнения.');
});

it('reuses same token on login and rotates it after logout', function (): void {
    $registerResponse = postJson('/api/v1/auth/register', [
        'name' => 'Forest User',
        'email' => 'forest@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $tokenBeforeLogout = $registerResponse->json('access_token');

    $firstLoginToken = postJson('/api/v1/auth/login', [
        'email' => 'forest@example.com',
        'password' => 'password',
    ])->json('access_token');

    expect($firstLoginToken)->toBe($tokenBeforeLogout);

    postJson('/api/v1/auth/logout', [], [
        'Authorization' => "Bearer {$tokenBeforeLogout}",
    ])->assertOk();

    $user = User::query()->where('email', 'forest@example.com')->firstOrFail()->fresh();
    expect($user->tokens()->count())->toBe(0);
    expect($user->api_token)->toBeNull();

    $tokenAfterLogout = postJson('/api/v1/auth/login', [
        'email' => 'forest@example.com',
        'password' => 'password',
    ])->json('access_token');

    expect($tokenAfterLogout)->not->toBe($tokenBeforeLogout);
});

it('blocks protected API endpoint for guests', function (): void {
    getJson('/api/v1/animals/me/recommendations')
        ->assertUnauthorized();
});

it('registers animal profile for authenticated user', function (): void {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $species = Species::factory()->create(['name' => 'Сова']);

    postJson('/api/v1/animals/register', [
        'name' => 'Луна',
        'nickname' => 'Лу',
        'species_id' => $species->id,
        'gender' => Gender::Female->value,
        'birth_date' => '2020-01-01',
        'best_friend_name' => 'Ваня',
    ])
        ->assertCreated()
        ->assertJsonPath('data.user_id', $user->id)
        ->assertJsonPath('data.name', 'Луна');
});

it('returns 401 json for me endpoint without token', function (): void {
    $this->get('/api/v1/auth/me')
        ->assertUnauthorized()
        ->assertJsonPath('message', 'Требуется авторизация.');
});

it('returns 401 json for logout endpoint without token', function (): void {
    $this->post('/api/v1/auth/logout')
        ->assertUnauthorized()
        ->assertJsonPath('message', 'Требуется авторизация.');
});

it('returns 422 for recommendations when user has no animal profile', function (): void {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    getJson('/api/v1/animals/me/recommendations')
        ->assertUnprocessable()
        ->assertJsonPath('message', 'Сначала создайте профиль зверя.');
});
