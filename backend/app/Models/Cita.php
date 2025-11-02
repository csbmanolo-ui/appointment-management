<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada con el modelo.
     */
    protected $table = 'citas'; // Plural correcto

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'paciente_id',
        'doctor_id',
        'fecha_hora_inicio',
        'fecha_hora_fin',
        'motivo_consulta',
        'estado',
        'notas_doctor', // Para la Sección 11 (Consultas)
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos.
     * Esto es genial para que las fechas sean objetos Carbon.
     */
    protected $casts = [
        'fecha_hora_inicio' => 'datetime',
        'fecha_hora_fin' => 'datetime',
    ];

    // --- RELACIONES ---
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
