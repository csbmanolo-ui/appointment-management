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
        // 1. Validamos los datos EXACTOS que envía el formulario de Angular
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|email|unique:pacientes,email', // Verifica único en tabla pacientes
            'telefono' => 'required|string|max:20',
            'seguro' => 'nullable|string|max:50'
        ]);

        // 2. Creamos el registro directamente en la tabla pacientes
        $paciente = \App\Models\Paciente::create($validated);

        // 3. Devolvemos el objeto creado (Código 201 = Created)
        return response()->json($paciente, 201);
    }
    /**
     * READ: Muestra un solo paciente.
     */
    public function show(Paciente $paciente) // Laravel usa el singular 'paciente'
    {
        return response()->json($paciente->load('user'));
    }
    public function update(Request $request, $id)
    {
        // 1. Buscar el paciente
        $paciente = \App\Models\Paciente::find($id);

        if (!$paciente) {
            return response()->json(['message' => 'Paciente no encontrado'], 404);
        }

        // 2. Validar (Igual que en store, pero el email debe ignorar el ID actual)
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            // La validación unique ignora el ID de ESTE paciente para que no de error si no cambias el email
            'email' => 'required|email|unique:pacientes,email,' . $id,
            'telefono' => 'required|string|max:20',
            'seguro' => 'nullable|string|max:50'
        ]);

        // 3. Actualizar
        $paciente->update($validated);

        return response()->json($paciente);
    }
    /**
     * DELETE: Borra un paciente directamente por su ID.
     */
    public function destroy($id)
    {
        // 1. Buscamos el paciente en la tabla 'pacientes'
        $paciente = \App\Models\Paciente::find($id);

        // 2. Si no existe, error 404
        if (!$paciente) {
            return response()->json(['message' => 'Paciente no encontrado'], 404);
        }

        // 3. Lo borramos
        $paciente->delete();

        return response()->json(['message' => 'Paciente eliminado correctamente']);
    }
}
