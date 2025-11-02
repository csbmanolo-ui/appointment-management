<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada con el modelo.
     */
    protected $table = 'pacientes'; // <-- Para evitar el error de plural

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'user_id',
        // Puedes añadir más campos específicos del paciente aquí
        // ej: 'fecha_nacimiento', 'direccion', etc.
    ];

    // --- RELACIONES ---
    // Un paciente pertenece a un Usuario (para login)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }
}

