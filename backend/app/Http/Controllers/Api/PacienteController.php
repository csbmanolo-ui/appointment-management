<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PacienteController extends Controller
{
    /**
     * READ: Muestra todos los pacientes.
     */
    public function index()
    {
        $pacientes = Paciente::with(['user'])->get();
        return response()->json($pacientes);
    }

    /**
     * CREATE: Registra un nuevo Paciente (Usuario + Perfil Paciente).
     */
    public function store(Request $request)
    {
        $request->validate([
            // Datos para la tabla 'users'
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            DB::beginTransaction();

            // 1. Crear el Usuario
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            // 2. Asignar el Rol "Paciente"
            $user->assignRole('Paciente');

            // 3. Crear el Paciente
            $paciente = $user->paciente()->create([]); // No hay campos extra por ahora

            DB::commit();

            return response()->json([
                'message' => 'Paciente creado exitosamente',
                'paciente' => $paciente->load('user')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al crear el paciente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * READ: Muestra un solo paciente.
     */
    public function show(Paciente $paciente) // Laravel usa el singular 'paciente'
    {
        return response()->json($paciente->load('user'));
    }

    /**
     * UPDATE: Actualiza un paciente.
     */
    public function update(Request $request, Paciente $paciente)
    {
        $user = $paciente->user; // Obtenemos el usuario relacionado

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        try {
            DB::beginTransaction();

            // 1. Actualizar el Usuario
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // 2. Actualizar el Paciente (no hay campos extra por ahora)
            // $paciente->update([...]);

            // Opcional: si se quiere cambiar la contraseña
            if ($request->filled('password')) {
                $request->validate(['password' => 'min:8|confirmed']);
                $user->update(['password' => Hash::make($request->password)]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Paciente actualizado exitosamente',
                'paciente' => $paciente->load('user')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al actualizar el paciente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE: Borra un Paciente (Borra el Usuario y el Paciente en cascada).
     */
    public function destroy(Paciente $paciente)
    {
        try {
            // Borramos el Usuario. La BBDD (onDelete('cascade'))
            // debería borrar automáticamente el registro 'pacientes'.
            User::destroy($paciente->user_id);

            return response()->json(null, 204); // 204: No Content

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el paciente',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
