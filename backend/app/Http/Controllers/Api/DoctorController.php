<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;   // <-- Para Transacciones
use Illuminate\Support\Facades\Hash; // <-- Para encriptar la contraseña

class DoctorController extends Controller
{
    /**
     * READ: Muestra todos los doctores con su info de usuario y especialidad.
     */
    public function index()
    {
        // 'with' carga las relaciones para evitar consultas N+1 (Eager Loading)
        $doctores = Doctor::with(['user', 'especialidad'])->get();
        return response()->json($doctores);
    }

    /**
     * CREATE: Registra un nuevo Doctor (Usuario + Perfil Doctor).
     */
    public function store(Request $request)
    {
        $request->validate([
            // Datos para la tabla 'users'
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',

            // Datos para la tabla 'doctores'
            'especialidad_id' => 'required|integer|exists:especialidades,id'
        ]);

        // Usamos una transacción para asegurar que ambas tablas se creen
        try {
            DB::beginTransaction();

            // 1. Crear el Usuario
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            // 2. Asignar el Rol
            $user->assignRole('Doctor');

            // 3. Crear el Doctor (usando la relación que definimos en User.php)
            $doctor = $user->doctor()->create([
                'especialidad_id' => $request->especialidad_id
            ]);

            DB::commit(); // Todo salió bien, confirmar cambios

            return response()->json([
                'message' => 'Doctor creado exitosamente',
                'doctor' => $doctor->load(['user', 'especialidad']) // Devolver el doctor con sus relaciones
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack(); // Algo salió mal, deshacer cambios
            return response()->json([
                'message' => 'Error al crear el doctor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * READ: Muestra un solo doctor.
     */
    public function show(Doctor $doctore) // Laravel usa 'doctore' (singular de doctores)
    {
        return response()->json($doctore->load(['user', 'especialidad']));
    }

    /**
     * UPDATE: Actualiza un doctor (Usuario + Perfil Doctor).
     */
    public function update(Request $request, Doctor $doctore)
    {
        $user = $doctore->user; // Obtenemos el usuario relacionado

        $request->validate([
            // Datos para 'users'
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id, // Ignora el email del propio usuario

            // Datos para 'doctores'
            'especialidad_id' => 'required|integer|exists:especialidades,id'
        ]);

        try {
            DB::beginTransaction();

            // 1. Actualizar el Usuario
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // 2. Actualizar el Doctor
            $doctore->update([
                'especialidad_id' => $request->especialidad_id
            ]);

            // Opcional: si se quiere cambiar la contraseña
            if ($request->filled('password')) {
                $request->validate(['password' => 'min:8|confirmed']);
                $user->update(['password' => Hash::make($request->password)]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Doctor actualizado exitosamente',
                'doctor' => $doctore->load(['user', 'especialidad'])
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al actualizar el doctor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE: Borra un Doctor (Borra el Usuario y el Doctor en cascada).
     */
    public function destroy(Doctor $doctore)
    {
        try {
            // Borramos el Usuario. La BBDD (onDelete('cascade'))
            // debería borrar automáticamente el registro 'doctores' asociado.
            User::destroy($doctore->user_id);

            return response()->json(null, 204); // 204: No Content

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el doctor',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
