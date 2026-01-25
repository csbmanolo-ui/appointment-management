<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cita;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctors';

    protected $fillable = [
        'nombre',
        'apellidos',
        'especialidad',
        'telefono',
        'email',
        'color'
    ];

    // --- RELACIÓN ACTIVADA ---
    public function citas()
    {
        return $this->hasMany(Cita::class);
    }
}
