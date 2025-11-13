<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario administrador
        User::create([
            'userDocumento' => '12345678',
            'userApellido' => 'Admin',
            'userNombre' => 'User',
            'userTelefono' => '123456789',
            'userProvincia' => 'Entre Rios',
            'userLocalidad' => 'Nogoyork',
            'userDomicilio' => 'pepe123',
            'userAceptado' => true,
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // O usar el factory
        User::factory(10)->create();
    }
}