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
    protected $table = 'pacientes';

    /**
     * Los atributos que se pueden asignar masivamente.
     * IMPORTANTE: Aquí deben estar los campos que envías desde Angular.
     */
    protected $fillable = [
        // 'user_id', // Descomenta si usas relación con usuarios más adelante
        'nombre',
        'apellidos',
        'email',
        'telefono',
        'seguro'
    ];

    // --- RELACIONES (Opcionales por ahora) ---

    // Un paciente pertenece a un Usuario (si decides vincularlos a login)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }
}
