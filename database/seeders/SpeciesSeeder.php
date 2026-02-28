<?php

namespace Database\Seeders;

use App\Models\Species;
use Illuminate\Database\Seeder;

class SpeciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['Сова', 'Заяц', 'Волк', 'Мышь'] as $speciesName) {
            Species::updateOrCreate(
                ['name' => $speciesName],
                ['name' => $speciesName]
            );
        }
    }
}
