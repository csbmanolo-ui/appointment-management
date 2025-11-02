<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Especialidad; //
use Illuminate\Http\Request;

class EspecialidadController extends Controller
{
    /**
     * READ: Muestra todas las especialidades.
     */
    public function index()
    {
        $especialidades = Especialidad::all();
        return response()->json($especialidades);
    }

    /**
     * CREATE: Guarda una nueva especialidad.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:especialidades',
        ]);

        $especialidad = Especialidad::create($request->all());

        return response()->json($especialidad, 201); // 201: Created
    }

    /**
     * READ: Muestra una sola especialidad.
     */
    public function show(Especialidad $especialidad)
    {
        return response()->json($especialidad);
    }

    /**
     * UPDATE: Actualiza una especialidad.
     */
    public function update(Request $request, Especialidad $especialidad)
    {
        $request->validate([
            // La regla 'unique' ignora el ID actual al editar
            'nombre' => 'required|string|max:255|unique:especialidades,nombre,' . $especialidad->id,
        ]);

        $especialidad->update($request->all());

        return response()->json($especialidad, 200); // 200: OK
    }

    /**
     * DELETE: Borra una especialidad.
     */
    public function destroy(Especialidad $especialidad)
    {
        $especialidad->delete();
        return response()->json(null, 204); // 204: No Content
    }
}
