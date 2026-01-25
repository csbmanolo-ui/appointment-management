<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Paciente;

class PacienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Paciente::create([
            'nombre' => 'Carlos',
            'apellidos' => 'Santana',
            'email' => 'carlos@guitar.com',
            'telefono' => '666111222',
            'seguro' => 'Sanitas'
        ]);

        Paciente::create([
            'nombre' => 'Lucía',
            'apellidos' => 'Méndez',
            'email' => 'lucia@mail.com',
            'telefono' => '611222333',
            'seguro' => 'Privado'
        ]);
    }
}
