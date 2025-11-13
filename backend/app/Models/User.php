<?php
//Modelo o plantilla que define cómo es un usuarioen mi app: que puede hacer y que datos tiene
namespace App\Models;

//use Illuminate\Contracts\Auth\MustVerifyEmail; //Interfaz que obliga a implementar métodos para verificar el email del usuario
use Illuminate\Database\Eloquent\Factories\HasFactory; //Trait que permite usar fábricas para crear instancias del modelo
use Illuminate\Foundation\Auth\User as Authenticatable; //Clase base para el modelo de usuario que incluye funcionalidades de autenticación
use Illuminate\Notifications\Notifiable; //Trait que permite enviar notificaciones al usuario
use Laravel\Sanctum\HasApiTokens; //Trait que permite gestionar tokens de API para el usuario
use Spatie\Permission\Traits\HasRoles; //Trait que permite asignar roles y permisos al usuario

/**
 * @mixin \Spatie\Permission\Traits\HasRoles
 */
class User extends Authenticatable
{   //Clase que representa a un usuario en el sistema
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function doctor()
    {

        return $this->hasOne(\App\Models\Doctor::class);
    }

    public function paciente()
    {

        return $this->hasOne(\App\Models\Paciente::class);
    }
}
