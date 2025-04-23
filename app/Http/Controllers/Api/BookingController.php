<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use App\Models\BookingModel;
use App\Models\RoomModel;
use App\Models\HotelModel;
use App\Models\CustomerModel;
use App\Models\BookingRoomModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class BookingController extends Controller
{

    public function createBooking()
    {
        $validator = Validator::make(request()->all(), [
            'customer_id' => 'required|exists:users,id',
            'hotels' => 'required|exists:hotels,id',
            'room_ids' => 'required|array',
            'room_ids.*' => 'exists:room,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'number_of_guests' => 'required|integer|min:1',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $checkIn = Carbon::parse(request()->check_in)->startOfDay();
        $checkOut = Carbon::parse(request()->check_out)->startOfDay();
    
        if ($checkIn->lt(Carbon::today())) {
            return response()->json(['error' => 'Check-in date must be today or later.'], 422);
        }
    
        if ($checkOut->lte($checkIn)) {
            return response()->json(['error' => 'Check-out date must be after check-in.'], 422);
        }


        $hotels = HotelModel::where('id', request()->hotels)
            ->whereHas('rooms', function ($query) {
                $query->whereIn('id', request()->room_ids);
            })
            ->first();
        // dd($hotels);
        if (!$hotels) {
            return response()->json(['error' => 'Hotel not found.'], 404);
        }


        $rooms = RoomModel::whereIn('id', request()->room_ids)->get();
        $nights = $checkIn->diffInDays($checkOut);
        $totalPrice = $rooms->sum('room_price') * $nights;
        $numberOfGuests = request()->number_of_guests ?? $rooms->sum('capacity');

        if ($numberOfGuests > $rooms->sum('capacity')) {
            return response()->json(['error' => 'Tamu melebihi kapasitas kamar.'], 422);
        }
    
        $booking = null;
        $rooms = RoomModel::whereIn('id', request()->room_ids)->get();
        $nights = $checkIn->diffInDays($checkOut);
        $totalPrice = $rooms->sum('price') * $nights *1000;
        $formattedTotalPrice = formatRupiah($totalPrice);
    
        // dd([
        //     'rooms' => $rooms->pluck('id'),
        //     'room_price_sum' => $totalPrice,
        //     'nights' => $nights,
        //     'total_price' => $totalPrice
        // ]);
        DB::transaction(function () use (&$booking, $checkIn, $checkOut, $totalPrice, $formattedTotalPrice, $hotels) {
            $booking = BookingModel::create([
                'customer_id' => request()->customer_id,
                'hotels' => request()->hotels,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'number_of_guests' => request()->number_of_guests,
                'status' => 'pending',
                'total_price' => $formattedTotalPrice,
            ]);
        });
        
        $booking->load('hotel');
           
        // dd($booking);
        

        return response()->json([
            'message' => 'Booking created successfully',
            'data' => $booking
            // 'hotel' => $booking->hotel(['hotel_name', 'hotel_id']),
        
            
        ], 201);
    }
    
   

 
    
    
    

}