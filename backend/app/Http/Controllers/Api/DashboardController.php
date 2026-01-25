<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Paciente;
use App\Models\Doctor;
use App\Models\Cita;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = Carbon::now()->toDateString();

        return response()->json([
            'total_pacientes' => Paciente::count(),
            'total_doctores'  => Doctor::count(),
            'citas_hoy_count' => Cita::whereDate('fecha_hora_inicio', $hoy)->count(),


            'citas_hoy' => Cita::with(['paciente', 'doctor'])
                ->whereDate('fecha_hora_inicio', $hoy)
                ->orderBy('fecha_hora_inicio', 'asc')
                ->get()
        ]);
    }
}
