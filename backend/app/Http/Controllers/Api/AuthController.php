<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // --- REGISTRO DE USUARIO ---
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // 'confirmed' busca un campo 'password_confirmation'
            'role' => 'required|string|in:Doctor,Paciente' // Solo permitimos registrar Doctores o Pacientes
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        // (Opcional) Aquí deberías crear también la entrada en la tabla 'pacientes' o 'doctores'
        // if ($request->role === 'Paciente') {
        //     \App\Models\Paciente::create(['user_id' => $user->id]);
        // } elseif ($request->role === 'Doctor') {
        //     // Faltaría la especialidad_id, es solo un ejemplo
        //     // \App\Models\Doctor::create(['user_id' => $user->id, 'especialidad_id' => 1]);
        // }

        return response()->json([
            'message' => 'Usuario registrado exitosamente. Inicie sesión.'
        ], 201);
    }

    // --- LOGIN DE USUARIO ---
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Intentar autenticar al usuario
        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        // Crear el token de Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [ // Devolvemos info del usuario para el frontend
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->getRoleNames()->first() // Obtenemos el primer rol (Admin, Doctor...)
            ]
        ], 200);
    }

    // --- LOGOUT DE USUARIO ---
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente'
        ], 200);
    }
}
