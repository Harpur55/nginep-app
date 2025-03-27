<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\HotelModel;
class RoomModel extends Model
{
    //

    protected $table = 'room';
    protected $primaryKey = 'id';

    protected $fillable = [
        'room_name',
        'type',
        'room_number',
        'room_price',
        'capacity',
        'price',
        'photo',
        'hotel_id',
    ];

    public function hotels() {
        return $this->belongsTo(HotelModel::class, 'hotel_id');
    }
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_room')
            ->withPivot('price')
            ->withTimestamps();
    }
}
