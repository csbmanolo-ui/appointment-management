<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cita;

class CitaController extends Controller
{
    /**
     * GET: Listar todas las citas
     * Trae también los datos del Paciente y el Doctor asociados.
     */
    public function index()
    {
        // 'with' carga las relaciones definidas en el Modelo Cita
        $citas = Cita::with(['paciente', 'doctor'])->get();
        return response()->json($citas);
    }

    /**
     * POST: Crear nueva cita
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id'       => 'required|exists:pacientes,id',
            'doctor_id'         => 'required|exists:doctors,id',
            'fecha_hora_inicio' => 'required|date', // Ej: 2025-10-20 10:00:00
            'fecha_hora_fin'    => 'required|date|after:fecha_hora_inicio', // Ej: 2025-10-20 10:30:00
            'motivo_consulta'   => 'required|string',
            'notas_doctor'      => 'nullable|string',
            'estado'            => 'nullable|string'
        ]);

        // Al crearla, el estado por defecto será 'pendiente' si no se envía
        $cita = Cita::create($validated);

        return response()->json($cita, 201);
    }

    /**
     * PUT: Modificar cita (Reprogramar, añadir notas, cambiar estado)
     */
    public function update(Request $request, $id)
    {
        $cita = Cita::find($id);

        if (!$cita) {
            return response()->json(['message' => 'Cita no encontrada'], 404);
        }

        // Validamos solo lo que venga en la petición
        $request->validate([
            'fecha_hora_inicio' => 'sometimes|date',
            'fecha_hora_fin'    => 'sometimes|date|after:fecha_hora_inicio',
            'doctor_id'         => 'sometimes|exists:doctors,id',
            'motivo_consulta'   => 'sometimes|string',
            'notas_doctor'      => 'nullable|string',
            'estado'            => 'sometimes|string'
        ]);

        $cita->update($request->all());

        return response()->json($cita);
    }

    /**
     * DELETE: Eliminar cita
     */
    public function destroy($id)
    {
        $cita = Cita::find($id);

        if (!$cita) {
            return response()->json(['message' => 'Cita no encontrada'], 404);
        }

        $cita->delete();
        return response()->json(['message' => 'Cita eliminada']);
    }
}
