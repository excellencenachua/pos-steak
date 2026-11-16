<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Cari user berdasarkan username
        $user = User::where('username', $request->username)->first();

        // 3. Cek apakah user ada dan password cocok
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Username atau password salah'
            ], 401);
        }

        // 4. Buat token akses (Sanctum)
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. Kembalikan response
        return response()->json([
            'message' => 'Login berhasil',
            'token'   => $token,
            'role'    => $user->role,
            'user'    => $user->only(['id', 'name', 'username', 'role']),
        ]);
    }
}