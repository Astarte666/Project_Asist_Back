<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Materias;

class InscripcionesMateriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estudiantes = User::role('estudiante')->get();

        if ($estudiantes->isEmpty()) {
            $this->command->info('No hay estudiantes para inscribir. Crea usuarios con rol "estudiante" primero.');
            return;
        }
        $materias = Materias::all();

        if ($materias->isEmpty()) {
            $this->command->info('No hay materias para inscribir. Ejecuta CarrerasMateriasSeeder primero.');
            return;
        }
        foreach ($estudiantes as $estudiante) {
            $materiasRandom = $materias->random(rand(3, 5));  

            foreach ($materiasRandom as $materia) {
                $estudiante->materias()->attach($materia->id, [
                    'fecha_inscripcion' => now()->subDays(rand(1, 30)),  
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Inscripciones a materias creadas correctamente para todos los estudiantes.');
    }


}//end

