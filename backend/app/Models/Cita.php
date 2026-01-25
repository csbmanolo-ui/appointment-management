<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Paciente;
use App\Models\Doctor;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';

    protected $fillable = [
        'paciente_id',
        'doctor_id',
        'fecha_hora_inicio',
        'fecha_hora_fin',
        'motivo_consulta',
        'notas_doctor',
        'estado',
    ];

    // Relación con Paciente (Fix para el 500)
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    // Relación con Doctor (Fix para el 500)
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
