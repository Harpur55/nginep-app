<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // ✅ Ini yang penting
use App\Models\CustomerModel;
use App\Models\BookingModel;
use App\Models\RoomModel;
use App\Models\HotelModel;
// use App\Models\BookingRoomModel;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
    
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
    
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }
    
        $customer = CustomerModel::updateOrCreate(
            ['user_id' => $user->id], 
            [
                'photo' => $photoPath,
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
    public function show(Request $request)
    {
        $customers = CustomerModel::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Data customer berhasil ditampilkan',
            'data' => $customers
        ], 200);
    }
    public function getBookingByCustomer(Request $request)
{
   
    $customer = auth()->user();

  
    $bookings = BookingModel::where('customer_id', $customer->id)
        ->with('rooms.hotel')  
        ->get();

    
    if ($bookings->isEmpty()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Booking tidak ditemukan.'
        ], 404);
    }

    // Format data hasil booking
    $results = $bookings->map(function ($booking) {
        return [
            'booking_id' => $booking->id,
            'hotel_name' => optional($booking->rooms->first()?->hotel)->hotel_name,  
            'rooms' => $booking->rooms->map(function ($room) {
                return [
                    'room_name' => $room->room_name,  
                    'pivot_price' => $room->pivot->price,  
                ];
            }),
        ];
    });


    return response()->json([
        'status' => 'success',
        'data' => $results,
    ]);
}
}
 
