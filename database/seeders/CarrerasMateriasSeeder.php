<?php

namespace Database\Seeders;

use App\Models\Carreras;
use App\Models\Materias;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarrerasMateriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crea 10 carreras y, por cada una, 20 unidades curriculares
        Carreras::factory(4)
            ->has(Materias::factory()->count(10))
            ->create();
    }
}
