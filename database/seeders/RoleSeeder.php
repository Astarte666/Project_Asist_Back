<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Crear Roles
        $adminRole = Role::firstOrCreate(['name' => 'administrador']);
        $profesorRole = Role::firstOrCreate(['name' => 'profesor']);
        $estudianteRole = Role::firstOrCreate(['name' => 'estudiante']);

        // Usuario admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'userApellido'  => 'Crack', 
                'userNombre'    => 'Admin',
                'userDocumento' => '10000000',
                'userTelefono'  => '1111111111',
                'userProvincia' => 'Ejemplo',
                'userLocalidad' => 'Ejemplo',
                'userDomicilio' => 'Ejemplo',
                'userAceptado'  => true,
                'email_verified_at' => now(),
                'password'      => Hash::make('zxcvbnm1'), 
            ]
        );
        $admin->assignRole('administrador');


        // Create an example Profesor user
        $profesor = User::firstOrCreate(
            ['email' => 'profesor@example.com'],
            [
                'userApellido'  => 'Profe',
                'userNombre'    => 'Pepe',
                'userDocumento' => '20000000',
                'userTelefono'  => '2222222222',
                'userProvincia' => 'Ejemplo',
                'userLocalidad' => 'Ejemplo',
                'userDomicilio' => 'Ejemplo',
                'userAceptado'  => true,
                'email_verified_at' => now(),
                'password'      => Hash::make('zxcvbnm1'),
            ]
        );
        $profesor->assignRole('profesor');


        // Create an example Estudiante user
        $estudiante = User::firstOrCreate(
            ['email' => 'estudiante@example.com'],
            [
                'userApellido'  => 'John',
                'userNombre'    => 'Doe',
                'userDocumento' => '30000000',
                'userTelefono'  => '3333333333',
                'userProvincia' => 'Ejemplo',
                'userLocalidad' => 'Ejemplo',
                'userDomicilio' => 'Ejemplo',
                'userAceptado'  => true,
                'email_verified_at' => now(),
                'password'      => Hash::make('zxcvbnm1'),
            ]
        );
        $estudiante->assignRole('estudiante');
        
        $this->command->info('Roles creados correctamente.');
    }
}