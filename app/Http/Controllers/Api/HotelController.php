<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\HotelModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class HotelController extends Controller
{
    //

    public function createHotel(Request $request){

        $validator = Validator::make($request->all(), [
            'hotel_name' => 'required | string | max:255',
            'hotel_address' => 'required | string | max:255',
            'hotel_phone' => 'required | string | max:255',
            'hotel_email' => 'required | string | max:255',
            'hotel_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'stars' => 'required | string | max:255',
            'description' => 'required | string | max:255',
           
           
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Validasi gagal, periksa kembali input Anda.',
                'errors' => $validator->errors()
            ], 400);
        }

        $hotel = HotelModel::create($validator->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Data hotel berhasil ditambahkan',
            'data' => $hotel
        ], 200);

    }
public function index(){
    $hotel = HotelModel::all();
    return response()->json([
        'status' => 'success',
        'message' => 'Data hotel berhasil ditampilkan',
        'data' => $hotel
    ], 200);
}

public function show($id){
    $hotel = HotelModel::find($id);
    if(!$hotel){
        return response()->json([
            'status' => 'failed',
            'message' => 'Data hotel tidak ditemukan',
        ], 404);
    }
    return response()->json([
        'status' => 'success',
        'message' => 'Data hotel berhasil ditampilkan',
        'data' => $hotel
    ], 200);
}
public function update(Request $request, $id){
    $hotel = HotelModel::find($id);
    if(!$hotel){
        return response()->json([
            'status' => 'failed',
            'message' => 'Data hotel tidak ditemukan',
        ], 404);
    }
    $validator = Validator::make($request->all(), [
        'hotel_name' => 'required | string | max:255',
        'hotel_address' => 'required | string | max:255',
        'hotel_phone' => 'required | string | max:255',
        'hotel_email' => 'required | string | max:255',
        'hotel_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'stars' => 'required | string | max:255',
        'description' => 'required | string | max:255',
       
    ]);
    $hotel->update($validator->validated());
    return response()->json([
        'status' => 'success',
        'message' => 'Data hotel berhasil diupdate',
        'data' => $hotel
    ], 200);
}
public function destroy($id){
    $hotel = HotelModel::find($id);
    if(!$hotel){
        return response()->json([
            'status' => 'failed',
            'message' => 'Data hotel tidak ditemukan',
        ], 404);
    }
    $hotel->delete();
    return response()->json([
        'status' => 'success',
        'message' => 'Data hotel berhasil dihapus',
    ], 200);    
}

public function getHotelRoom($id){
    $hotel = HotelModel::find($id);
    if(!$hotel){
        return response()->json([
            'status' => 'failed',
            'message' => 'Data hotel tidak ditemukan',
        ], 404);
    }
    $rooms = $hotel->rooms;
    return response()->json([
        'status' => 'success',
        'message' => 'Data room berhasil ditampilkan',
        'data' => $rooms
    ], 200);        
}
}

