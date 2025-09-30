<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        // Create Permissions (if needed, but for this case, roles are enough)
        // Example: Permission::create(['name' => 'create carreras']);
        // Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'administrador']);
        $profesorRole = Role::firstOrCreate(['name' => 'profesor']);
        $estudianteRole = Role::firstOrCreate(['name' => 'estudiante']);
        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('zxcvbnm1'), // Cambiar

            ]
        );
        $admin->assignRole('administrador');
        // Create an example Profesor user
        $profesor = User::firstOrCreate(
            ['email' => 'profesor@example.com'],
            [
                'name' => 'Profesor User',
                'password' => Hash::make('zxcvbnm1'),
            ]
        );
        $profesor->assignRole('profesor');
        // Create an example Estudiante user
        $estudiante = User::firstOrCreate(
            ['email' => 'estudiante@example.com'],
            [
                'name' => 'Estudiante User',
                'password' => Hash::make('zxcvbnm1'),
            ]
        );
        $estudiante->assignRole('estudiante');
        $this->command->info('Roles and initial users created successfully!');
    }
}
