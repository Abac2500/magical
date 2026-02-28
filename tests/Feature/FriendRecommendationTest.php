<?php

use App\Enums\Gender;
use App\Models\Animal;
use App\Models\Species;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;

uses(RefreshDatabase::class);

function createAnimalForRecommendation(array $attributes = []): Animal
{
    return Animal::factory()->create(array_merge([
        'name' => 'Безымянный',
        'nickname' => null,
        'gender' => Gender::Male->value,
        'birth_date' => '2020-01-01',
        'best_friend_name' => 'Гоша',
    ], $attributes));
}

function actingAsAnimalOwner(Animal $animal): User
{
    $user = User::factory()->create();
    $animal->update(['user_id' => $user->id]);
    Sanctum::actingAs($user);

    return $user;
}

it('uses name similarity as first recommendation rule', function (): void {
    $owl = Species::factory()->create(['name' => 'Сова']);
    $hare = Species::factory()->create(['name' => 'Заяц']);

    $animal = createAnimalForRecommendation([
        'name' => 'Луна',
        'species_id' => $owl->id,
        'gender' => Gender::Female->value,
        'best_friend_name' => 'Ваня',
    ]);

    $nameMatch = createAnimalForRecommendation([
        'name' => 'Вася',
        'species_id' => $hare->id,
    ]);

    createAnimalForRecommendation([
        'name' => 'Олег',
        'nickname' => 'Ваня',
        'species_id' => $hare->id,
    ]);

    actingAsAnimalOwner($animal);

    $response = getJson('/api/v1/animals/me/recommendations');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.id'))->toBe($nameMatch->id);
});

it('falls back to nickname similarity when name similarity has no matches', function (): void {
    $wolf = Species::factory()->create(['name' => 'Волк']);
    $mouse = Species::factory()->create(['name' => 'Мышь']);

    $animal = createAnimalForRecommendation([
        'name' => 'Лили',
        'species_id' => $wolf->id,
        'gender' => Gender::Female->value,
        'best_friend_name' => 'Том',
    ]);

    $nicknameMatch = createAnimalForRecommendation([
        'name' => 'Карл',
        'nickname' => 'Том',
        'species_id' => $mouse->id,
    ]);

    createAnimalForRecommendation([
        'name' => 'Оливер',
        'species_id' => $wolf->id,
        'gender' => Gender::Male->value,
    ]);

    actingAsAnimalOwner($animal);

    $response = getJson('/api/v1/animals/me/recommendations');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.id'))->toBe($nicknameMatch->id);
});

it('falls back to same species with opposite gender', function (): void {
    $species = Species::factory()->create(['name' => 'Сова']);

    $animal = createAnimalForRecommendation([
        'name' => 'Эмма',
        'species_id' => $species->id,
        'gender' => Gender::Female->value,
        'best_friend_name' => 'Неизвестно',
    ]);

    $firstOpposite = createAnimalForRecommendation([
        'name' => 'Борис',
        'species_id' => $species->id,
        'gender' => Gender::Male->value,
    ]);
    $secondOpposite = createAnimalForRecommendation([
        'name' => 'Кирилл',
        'species_id' => $species->id,
        'gender' => Gender::Male->value,
    ]);

    createAnimalForRecommendation([
        'name' => 'Ирина',
        'species_id' => $species->id,
        'gender' => Gender::Female->value,
    ]);

    actingAsAnimalOwner($animal);

    $response = getJson('/api/v1/animals/me/recommendations');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(2);
    expect(collect($response->json('data'))->pluck('id')->all())
        ->toBe([$firstOpposite->id, $secondOpposite->id]);
});

it('falls back to same species and excludes friends with 10 result cap', function (): void {
    $species = Species::factory()->create(['name' => 'Заяц']);
    $otherSpecies = Species::factory()->create(['name' => 'Мышь']);

    $animal = createAnimalForRecommendation([
        'name' => 'Жанна',
        'species_id' => $species->id,
        'gender' => Gender::Female->value,
        'best_friend_name' => 'Несовпадение',
    ]);

    $friend = createAnimalForRecommendation([
        'name' => 'Друг',
        'species_id' => $species->id,
        'gender' => Gender::Female->value,
    ]);
    $animal->friends()->syncWithoutDetaching([$friend->id]);
    $friend->friends()->syncWithoutDetaching([$animal->id]);

    Animal::factory()->count(12)->create([
        'species_id' => $species->id,
        'gender' => Gender::Female->value,
        'nickname' => null,
        'best_friend_name' => 'Х',
    ]);

    createAnimalForRecommendation([
        'name' => 'Чужой',
        'species_id' => $otherSpecies->id,
        'gender' => Gender::Female->value,
    ]);

    actingAsAnimalOwner($animal);

    $response = getJson('/api/v1/animals/me/recommendations');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(10);
    expect(collect($response->json('data'))->pluck('id'))
        ->not->toContain($friend->id);
    expect(collect($response->json('data'))->pluck('species_id')->unique()->all())
        ->toBe([$species->id]);
});
