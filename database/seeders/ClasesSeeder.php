<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Clases;
use App\Models\Materias;


class ClasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materias = Materias::all();
        if ($materias->isEmpty()) {
            $this->command->info('No hay materias para asociar clases. Ejecuta primero CarrerasMateriasSeeder.');
            return;
        }
        foreach ($materias as $materia) {
            Clases::factory()
                ->count(3) 
                ->create([
                    'materias_id' => $materia->id,
                ]);
        }

        $this->command->info('Clases creadas correctamente para todas las materias.');
    }
}
