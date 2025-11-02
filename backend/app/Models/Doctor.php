<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada con el modelo.
     */
    protected $table = 'doctores';

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'user_id',
        'especialidad_id',
    ];


    // Un doctor pertenece a un Usuario (para login)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un doctor pertenece a una Especialidad
    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class);
    }

    public function citas()
    {
        return $this->hasMany(Cita::class);

    }
}
