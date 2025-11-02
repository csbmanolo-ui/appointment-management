<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- ¡Importante para saber quién está logueado!

class CitaController extends Controller
{
    /**
     * READ: Muestra una lista de citas.
     * La lógica cambia según el rol del usuario.
     */
    public function index()
    {
        $user = Auth::user();
        $citas = [];

        if ($user->hasRole('Admin')) {
            // Admin: Ve todas las citas con toda la info
            $citas = Cita::with(['paciente.user', 'doctor.user', 'doctor.especialidad'])->get();
        }
        elseif ($user->hasRole('Doctor')) {
            // Doctor: Ve solo sus citas, con la info del paciente
            $doctor = $user->doctor;
            $citas = $doctor->citas()->with(['paciente.user'])->get();
        }
        elseif ($user->hasRole('Paciente')) {
            // Paciente: Ve solo sus citas, con la info del doctor
            $paciente = $user->paciente;
            $citas = $paciente->citas()->with(['doctor.user', 'doctor.especialidad'])->get();
        }

        return response()->json($citas);
    }

    /**
     * CREATE: Guarda una nueva cita.
     * Usado por Pacientes o por un Admin en nombre de un paciente.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'doctor_id' => 'required|integer|exists:doctores,id',
            'fecha_hora_inicio' => 'required|date',
            'fecha_hora_fin' => 'required|date|after:fecha_hora_inicio',
            'motivo_consulta' => 'required|string',
        ]);

        $pacienteId = null;

        if ($user->hasRole('Admin')) {
            // Si es Admin, debe especificar qué paciente pide la cita
            $request->validate(['paciente_id' => 'required|integer|exists:pacientes,id']);
            $pacienteId = $request->paciente_id;
        } else {
            // Si es Paciente, la cita es para él mismo
            $pacienteId = $user->paciente->id;
        }

        $cita = Cita::create([
            'paciente_id' => $pacienteId,
            'doctor_id' => $request->doctor_id,
            'fecha_hora_inicio' => $request->fecha_hora_inicio,
            'fecha_hora_fin' => $request->fecha_hora_fin,
            'motivo_consulta' => $request->motivo_consulta,
            'estado' => 'pendiente' // Estado por defecto
        ]);

        return response()->json($cita, 201);
    }

    /**
     * READ: Muestra una cita específica.
     * Con seguridad para que solo el dueño o un Admin la vean.
     */
    public function show(Cita $cita)
    {
        $user = Auth::user();

        // Admin puede ver todo
        if ($user->hasRole('Admin')) {
            return response()->json($cita->load(['paciente.user', 'doctor.user']));
        }

        // Doctor solo ve sus citas
        if ($user->hasRole('Doctor') && $user->doctor->id === $cita->doctor_id) {
             return response()->json($cita->load(['paciente.user']));
        }

        // Paciente solo ve sus citas
        if ($user->hasRole('Paciente') && $user->paciente->id === $cita->paciente_id) {
             return response()->json($cita->load(['doctor.user', 'doctor.especialidad']));
        }

        // Si no es ninguno, no está autorizado
        return response()->json(['message' => 'No autorizado para ver esta cita'], 403);
    }

    /**
     * UPDATE: Actualiza una cita.
     * Usado por un Doctor (para añadir notas) o un Admin.
     */
    public function update(Request $request, Cita $cita)
    {
        $user = Auth::user();

        // Un Doctor solo puede actualizar sus propias citas (para Sección 11)
        if ($user->hasRole('Doctor')) {
            if ($user->doctor->id !== $cita->doctor_id) {
                return response()->json(['message' => 'No autorizado'], 403);
            }

            // El doctor solo puede cambiar el estado o las notas
            $data = $request->validate([
                'estado' => 'sometimes|string|in:pendiente,confirmada,cancelada,completada',
                'notas_doctor' => 'nullable|string'
            ]);
            $cita->update($data);

        }
        // Un Admin puede actualizar todo (ej. cambiar el doctor)
        elseif ($user->hasRole('Admin')) {
            $data = $request->validate([
                'doctor_id' => 'sometimes|integer|exists:doctores,id',
                'fecha_hora_inicio' => 'sometimes|date',
                'fecha_hora_fin' => 'sometimes|date|after:fecha_hora_inicio',
                'motivo_consulta' => 'sometimes|string',
                'estado' => 'sometimes|string|in:pendiente,confirmada,cancelada,completada',
                'notas_doctor' => 'nullable|string'
            ]);
            $cita->update($data);
        }

        return response()->json($cita, 200);
    }

    /**
     * DELETE: Borra una cita.
     * (Solo el Admin puede hacer esto, según routes/api.php)
     */
    public function destroy(Cita $cita)
    {
        $cita->delete();
        return response()->json(null, 204);
    }
}
