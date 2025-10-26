<?php
// Seeder principal para la base de datos
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,//Llamada al seeder de roles
            AdminUserSeeder::class,//Llamada al seeder del usuario administrador
        ]);
    }
}
