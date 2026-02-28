<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Models\Animal;
use App\Models\Species;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Animal>
 */
class AnimalFactory extends Factory
{
    protected $model = Animal::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->firstName(),
            'nickname' => fake()->optional()->firstName(),
            'species_id' => Species::factory(),
            'gender' => fake()->randomElement([Gender::Male, Gender::Female])->value,
            'birth_date' => fake()->date(),
            'best_friend_name' => fake()->firstName(),
        ];
    }
}
