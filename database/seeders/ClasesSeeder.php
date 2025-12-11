<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Clases;
use App\Models\Materias;
use Carbon\Carbon;

class ClasesSeeder extends Seeder
{
    public function run(): void
    {
        $materias = Materias::all();
        
        if ($materias->isEmpty()) {
            $this->command->info('No hay materias. Ejecuta primero CarrerasMateriasSeeder.');
            return;
        }

        foreach ($materias as $materia) {
            for ($i = -60; $i <= 14; $i += 7) {
                Clases::create([
                    'materias_id' => $materia->id,
                    'fecha' => Carbon::now()->addDays($i)->format('Y-m-d'),
                ]);
            }
        }

        $this->command->info('Clases creadas correctamente para todas las materias.');
    }
}