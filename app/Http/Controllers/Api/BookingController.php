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
    public function store(Request $request)
    {
        
        {
            $validator = Validator::make($request->all(), [
                'customer_id' => 'required|exists:customer,id',
                'number_of_guests' => 'required|integer|min:1',
                'room_ids' => 'required|array',
                'room_ids.*' => 'exists:room,id',
                'check_in' => 'required|date',
                'check_out' => 'required|date',
                // 'total_price' => 'required|numeric',
            ]);
        
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
        
            // Parsing check-in dan check-out menggunakan Carbon
            $checkIn = Carbon::parse($request->check_in)->startOfDay();
            $checkOut = Carbon::parse($request->check_out)->startOfDay();
        
            // Validasi tambahan menggunakan Carbon
            if ($checkIn->lt(Carbon::today())) {
                return response()->json(['error' => 'Check-in date must be today or later.'], 422);
            }
        
            if ($checkOut->lte($checkIn)) {
                return response()->json(['error' => 'Check-out date must be after the check-in date.'], 422);
            }
        
            try {
                DB::beginTransaction();

                $nights = Carbon::parse($request->check_in)->diffInDays(Carbon::parse($request->check_out));
        

                $rooms = RoomModel::whereIn('id', $request->room_ids)->get(['id', 'room_name', 'hotel_id']);
                $totalPrice = $rooms->sum('room_price') * $nights;
        
        
                // Buat booking baru dengan tanggal yang telah diproses oleh Carbon
                $booking = BookingModel::create([
                    'customer_id' => $request->customer_id,
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'number_of_guests' => $request->number_of_guests ?? RoomModel::whereIn('id', $request->room_ids)->sum('capacity'),
                    // 'total_price' => $totalPrice,
                    'status' => 'pending',
                ]);
                if (!$booking) {
                    throw new \Exception('Booking failed to create');
                    
                }
              
              
                $hotelIds = $rooms->pluck('hotel_id')->unique();
                $hotels = HotelModel::whereIn('id', $hotelIds)->get(['id', 'hotel_name']);
        
                
                $roomsData = $rooms->map(function ($room) use ($hotels) {
                    $hotelName = $hotels->firstWhere('id', $room->hotel_id)?->hotel_name ?? 'Unknown Hotel';
                    return [
                        'room_id' => $room->id,
                        'name' => $room->room_name, // Gunakan 'name' sesuai permintaan API
                        'hotel_name' => $hotelName,
                    ];
                });
        
                DB::commit();
        
                return response()->json([
                    'message' => 'Booking successfully created',
                    'booking' => [
                        'id' => $booking->id,
                        'customer_id' => $booking->customer_id,
                        'check_in' => $booking->check_in,
                        'check_out' => $booking->check_out,
                        'status' => $booking->status,
                        // 'total_price' => $totalPrice,
                        'number_of_guests' => $booking->number_of_guests,
                        'rooms' => $roomsData // Tambahkan daftar kamar ke response
                    ]
                ], 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'error' => 'Failed to create booking.',
                    'message' => $e->getMessage()
                ], 500);
            }
        }
    }
}


