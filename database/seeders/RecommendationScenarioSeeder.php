<?php

namespace Database\Seeders;

use App\Enums\Gender;
use App\Models\Animal;
use App\Models\Species;
use Illuminate\Database\Seeder;

class RecommendationScenarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(SpeciesSeeder::class);

        $owl = Species::where('name', 'Сова')->firstOrFail();
        $hare = Species::where('name', 'Заяц')->firstOrFail();

        $seedAnimals = [
            [
                'name' => 'Луна',
                'nickname' => 'Лу',
                'species_id' => $owl->id,
                'gender' => Gender::Female->value,
                'birth_date' => '2020-03-10',
                'best_friend_name' => 'Ваня',
            ],
            [
                'name' => 'Вася',
                'nickname' => 'Шустрик',
                'species_id' => $hare->id,
                'gender' => Gender::Male->value,
                'birth_date' => '2019-05-02',
                'best_friend_name' => 'Луна',
            ],
            [
                'name' => 'Тимофей',
                'nickname' => 'Ваня',
                'species_id' => $hare->id,
                'gender' => Gender::Male->value,
                'birth_date' => '2018-01-11',
                'best_friend_name' => 'Луна',
            ],
        ];

        foreach ($seedAnimals as $attributes) {
            Animal::create($attributes);
        }
    }
}
