<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'userDocumento' => $this->faker->unique()->numerify('########'),
            'userApellido' => $this->faker->lastName(),
            'userNombre' => $this->faker->firstName(),
            'userTelefono' => $this->faker->phoneNumber(),
            'userProvincia' => $this->faker->state(),
            'userLocalidad' => $this->faker->city(),
            'userDomicilio' => $this->faker->streetAddress(),
            'userAceptado' => $this->faker->boolean(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}