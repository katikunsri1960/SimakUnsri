<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Register user baru (pakai params/form-data)
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|string|in:admin,univ,fakultas,prodi,dosen,mahasiswa,bak,perpus',
            'fk_id' => 'nullable|integer'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => $validated['role'] ?? User::MAHASISWA,
            'fk_id' => $validated['fk_id'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        /** @var \App\Models\User $user */
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'token' => $token
        ], 201);
    }

    /**
     * Login user (pakai params/form-data)
     */
    public function login(Request $request)
{
    // Validasi
    $request->validate([
        'login' => 'required|string', // bisa email atau username
        'password' => 'required|string',
    ]);

    // Field login (EMAIL / USERNAME)
    $login = trim($request->login);

    $field = filter_var($login, FILTER_VALIDATE_EMAIL)
        ? 'email'
        : 'username';

    //Proses autentikasi 
    if (!Auth::attempt([
        $field => $login,
        'password' => $request->password,
    ])) {
        throw ValidationException::withMessages([
            'login' => ['Username / Email atau password salah.'],
        ]);
    }

    //Ambil user & buat token
    /** @var \App\Models\User $user */
    $user = Auth::user();

    $token = $user->createToken('api_token')->plainTextToken;

    //  Response
    return response()->json([
        'message' => 'Login berhasil',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
        ],
        'token' => $token,
    ]);
}

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }
}
