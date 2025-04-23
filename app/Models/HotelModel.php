<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelModel extends Model
{
    //
    protected $table = 'hotels';
    protected $primaryKey = 'id';

    protected $fillable = [
    
        'hotel_name',
        'hotel_address',
        'hotel_phone',
        'hotel_email',
        'hotel_photo',
        'stars',
        'description',
        'room_number',
    ];

    public function booking() {
        return $this->hasMany(BookingModel::class);
    }


    public function rooms()
    {
        return $this->hasMany(RoomModel::class, 'hotel_id');
    }
    // public function room() {

    //     return $this->hasMany(RoomModel::class, 'hotels_id','id')
    //                                             ->select(['id','room_name','room_type','price','capacity','room_photo','hotel_id']);            
    // }
}
