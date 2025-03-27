<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    
public function register(Request $request){


    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|confirmed',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->messages()], 422);
    }

    // Ambil data valid & hash password
    $validatedData = $validator->validated();
    $validatedData['password'] = Hash::make($validatedData['password']);

    // Buat user baru
    $user = User::create($validatedData);

    // Buat token
    $token = $user->createToken('auth_token')->plainTextToken;

    // Response
    return response()->json([
        'token' => $token,
        'status' => 'success',
        'message' => 'Data customer berhasil ditambahkan',
        'data' => $user
    ], 200);

}
public function login( Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // Coba autentikasi user
    if (!auth()->attempt($credentials)) {
        return response()->json([
            'status' => 'failed',
            'message' => 'Unauthorized'
        ], 401);
    }

    // Ambil data user yang sudah login
    $user = auth()->user();

    // Hapus token lama (jika ingin sesi eksklusif)
    $user->tokens()->delete();

    // Buat token baru
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'status' => 'success',
        'message' => 'Login berhasil'
    ], 200);
}



}
