<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RoomModel;
use App\Models\HotelModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RoomController extends Controller
{

    

    public function getHotelById($hotel_id) {
        $hotel = HotelModel::with('rooms')->find($hotel_id);
    
        if (!$hotel) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Data hotel tidak ditemukan',
            ], 404);
        }
    
        return response()->json([
            'status' => 'success',
            'data' => $hotel
        ], 200);
    }
  
    
    public function create(Request $request) {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'room_name' => 'required|string|max:255',
            'room_number' => 'required|string|max:255|unique:room',
            'type' => 'required|string|max:255',
            'price' => 'required|integer',
            'capacity' => 'required|integer',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'hotel_id' => 'required|integer|exists:hotels,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Validasi gagal, periksa kembali input Anda.',
                'errors' => $validator->errors()
            ], 400);
        }
    
        // Buat room baru
        $room = RoomModel::create($validator->validated());
    
        return response()->json([
            'status' => 'success',
            'message' => 'Data room berhasil ditambahkan',
            'data' => $room
        ], 201);
    }
    public function index(){
        $room = RoomModel::with('hotels')->get();
        return response()->json([
            'status' => 'success',
            'data' => $room,
            'message' => 'Data room berhasil ditampilkan'
        ], 200);
    }
    public function show($id){
        $room = RoomModel::with('hotels')->find($id);
        if(!$room){
            return response()->json([
                'status' => 'failed',
                'message' => 'Data room tidak ditemukan'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $room,
            'message' => 'Data room berhasil ditampilkan'
        ], 200);
    }
    
    public function update(Request $request, $id){
        $room = RoomModel::findOrFail($id);
        $room->update($request->all());
        return response()->json([
            'status' => 'success',
            'data' => $room,
            'message' => 'Data room berhasil diupdate'
        ], 200);
    }
    
    public function destroy($id){
        $room = RoomModel::findOrFail($id);
        $room->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Data room berhasil dihapus'
        ], 200);
    }
}
