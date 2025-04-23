<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerModel;
use App\Models\RoomModel;
use App\Models\HotelModel;

class BookingModel extends Model
{
    //

    protected $table = 'booking';
    protected $primaryKey = 'id';
    protected $fillable = [
        'customer_id',
        'check_in',
        'check_out',
        'room_id',
        'number_of_guests',
        'status',
        'total_price',
    ];
    public function customer() {
        return $this->belongsTo(CustomerModel::class, 'customer_id');
    }

    public function rooms()
    {
        return $this->belongsToMany(RoomModel::class, 'booking_room', 'booking_id', 'room_id','room_name')
                    ->withPivot('price')
                    ->withTimestamps(); // opsional kalau tabelmu pakai timestamps
    }
  
   


    
   


}
