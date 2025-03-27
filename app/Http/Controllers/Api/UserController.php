<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Data user berhasil ditampilkan',
            'data' => $users
        ], 200);
    }
}
