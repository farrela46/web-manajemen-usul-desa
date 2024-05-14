<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'nama' => 'required|string',
            'NIK' => 'required|integer|unique:users,NIK'
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'nama' => $request->nama,
            'NIK' => $request->NIK,
            'role' => 'user',
            'status' => 'unverified'
        ]);

        if (!$user) {
            return response()->json([
                'message' => 'Server error'
            ], 500);
        }

        // Attempt to authenticate user after successful registration
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Server error during login'
            ], 500);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'role' => $user->role,
            'username' => $user->username,
            'NIK' => $user->NIK,
            'status' => $user->status,
        ], 201);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Email atau Password salah'
            ], 401);
        }
        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'role' => $user->role,
            'nama' => $user->nama,
            'NIK' => $user->NIK,
            'status' => $user->status,
        ]);
    }

    function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Logout berhasil!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal Logout.'], 500);
        }
    }

    public function verified(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User tidak dapat ditemukan.'], 404);
        }

        $user->update([
            'status' => 'verified'
        ]);

        return response()->json(['message' => 'Berhasil memverifikasi User'], 200);
    }
}
