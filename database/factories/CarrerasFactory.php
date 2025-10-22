<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Carreras;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Carreras>
 */
class CarrerasFactory extends Factory
{
    // Defino el modelo
    protected $model = Carreras::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'carreNombre' => $this->faker->unique()->sentence(3)        ];
    }

    // Crea un nuevo método 'configure' para definir la secuencia
    /* public function configure(): static
    {
        return $this->sequence(
            ['carreNombre' => 'Tecnicatura en Software'],
            ['carreNombre' => 'Profesorado de Primaria'],
            ['carreNombre' => 'Profesorado de Matemática'],
            ['carreNombre' => 'Tecnicatura de Enfermería'],
        );
    } */


}//end 
