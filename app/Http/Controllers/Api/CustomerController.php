<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth; // ✅ Tambahkan ini
use App\Models\CustomerModel; // ✅ Pastikan model sesuai
use App\Models\User;

class CustomerController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = Auth::user(); // ✅ Ambil user yang sedang login

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User tidak ditemukan atau belum login.'
            ], 401);
        }

        $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            'name' => 'required|string|max:255',
            'gender' => 'nullable|string',
            'nationality' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        // ✅ Pastikan model CustomerModel yang digunakan
        $customer = CustomerModel::updateOrCreate(
            ['user_id' => $user->id], // ✅ Cari berdasarkan `user_id`
            [
                'photo' => $request->photo,
                'name' => $request->name,
                'gender' => $request->gender,
                'nationality' => $request->nationality,
                'birth_date' => $request->birth_date,
                'phone' => $request->phone,
                'address' => $request->address
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Profil customer berhasil diperbarui',
            'data' => $customer
        ], 200);
    }

    public function index()
    {
        $customers = CustomerModel::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Data customer berhasil ditampilkan',
            'data' => $customers
        ], 200);
    }
}
