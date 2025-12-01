<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\Clases;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Clases>
 */
class ClasesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'materias_id' => null, 
            'fecha' => Carbon::now()->addDays($this->faker->numberBetween(1, 30)), 
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
