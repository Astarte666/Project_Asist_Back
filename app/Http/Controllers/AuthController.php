<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8',
                'rol' => 'required|in:administrador,profesor,estudiante',
                'userDocumento' => 'required|string|unique:users',
                'userApellido' => 'required|string',
                'userNombre' => 'required|string',
                'userTelefono' => 'required|string',
                'userDomicilio' => 'required|string',
                'userProvincia' => 'required|string',
                'userLocalidad' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $user = User::create([
                'userDocumento' => $request->userDocumento,
                'userApellido' => $request->userApellido,
                'userNombre' => $request->userNombre,
                'userTelefono' => $request->userTelefono,
                'userDomicilio' => $request->userDomicilio,
                'userProvincia' => $request->userProvincia,
                'userLocalidad' => $request->userLocalidad,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole($request->rol);

            return response()->json([
                'token' => $user->createToken('API Token')->plainTextToken,
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al registrar usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',

        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->with('roles')->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        // Obtén solo los nombres de los roles
        $roleNames = $user->roles->pluck('name'); // Utiliza pluck para obtener solo la columna 'name'

        return response()->json([
            'token' => $user->createToken('API Token')->plainTextToken,
            'user' => [
                'id' => $user->id,
                'userDocumento' =>  $user->userDocumento,
                'userApellido' =>  $user->userApellido,
                'userNombre' =>  $user->userNombre,
                'userTelefono' => $user->userTelefono,
                'userDomicilio' => $user->userDomicilio,
                'userProvincia' => $user->userProvincia,
                'userLocalidad' => $user->userLocalidad,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ],
            'rol' => $roleNames->first() // Usar first() para evitar error si no hay roles
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }
}
