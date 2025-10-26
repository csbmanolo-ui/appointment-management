<?php
// Seeder para crear roles iniciales en la base de datos
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
// Clase que representa un rol en el sistema de permisos
class RolesSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Doctor']);
        Role::create(['name' => 'Paciente']);
    }
}
