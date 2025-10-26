<?php
// Seeder para crear el usuario administrador inicial en la base de datos
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Modelo de usuario
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('Sombrilla1234')//contraseña hasheada
        ]);

        $admin->assignRole('Admin');
    }
}
