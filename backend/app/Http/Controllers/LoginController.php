<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                // Obtén el token
                $token = $user->createToken('auth-token')->accessToken;
                return response()->json(['code' => 200, 'token' => $token, 'message' => 'Bienvenido ' . $user->name]);
            }
            return response()->json(['code' => 401, 'message' => 'Credenciales inválidas'], 401);
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'message' => 'Error al iniciar sesión', 'error' => $e->getMessage()], 500);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = User::create($request->all());
            $token = $user->createToken('auth-token')->accessToken;
            DB::commit();
            return response()->json([
                'code' => 200,
                'token' => $token,
                'message' => 'Usuario registrado correctamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['code' => 500, 'message' => 'Error al registrar usuario', 'error' => $e->getMessage()], 500);
        }
    }
}
