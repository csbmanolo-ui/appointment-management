<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;

class DoctorController extends Controller
{
    /**
     * GET: Listar todos los doctores
     */
    public function index()
    {
        // Devolvemos todos los registros de la tabla 'doctors'
        return response()->json(Doctor::all());
    }

    /**
     * POST: Crear un nuevo doctor
     */
    public function store(Request $request)
    {
        // 1. Validamos los datos que vienen del formulario Angular
        $validated = $request->validate([
            'nombre'       => 'required|string|max:255',
            'apellidos'    => 'required|string|max:255',
            'especialidad' => 'required|string|max:255',
            'email'        => 'required|email|unique:doctors,email', // Verifica único en tabla doctors
            'telefono'     => 'required|string|max:20',
        ]);

        // 2. Creamos el registro (el campo 'color' se pone automático por defecto en la BD si no se envía)
        $doctor = Doctor::create($validated);

        // 3. Devolvemos éxito
        return response()->json($doctor, 201);
    }

    /**
     * PUT: Actualizar un doctor existente
     */
    public function update(Request $request, $id)
    {
        // 1. Buscamos el doctor
        $doctor = Doctor::find($id);

        if (!$doctor) {
            return response()->json(['message' => 'Doctor no encontrado'], 404);
        }

        // 2. Validamos (ignora el email propio para que no de error de duplicado)
        $validated = $request->validate([
            'nombre'       => 'required|string|max:255',
            'apellidos'    => 'required|string|max:255',
            'especialidad' => 'required|string|max:255',
            'email'        => 'required|email|unique:doctors,email,' . $id,
            'telefono'     => 'required|string|max:20',
        ]);

        // 3. Actualizamos
        $doctor->update($validated);

        return response()->json($doctor);
    }

    /**
     * DELETE: Eliminar un doctor
     */
    public function destroy($id)
    {
        $doctor = Doctor::find($id);

        if (!$doctor) {
            return response()->json(['message' => 'Doctor no encontrado'], 404);
        }

        $doctor->delete();

        return response()->json(['message' => 'Doctor eliminado correctamente']);
    }
}
