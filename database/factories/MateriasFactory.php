<?php

namespace Database\Factories;

use App\Models\Carreras;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Materias>
 */
class MateriasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'matNombre' => $this->faker->unique()->words(2, true),
            'carreras_id' => Carreras::factory(),
        ];
    }
}
