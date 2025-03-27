<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerModel;
use App\Models\RoomModel;

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
        'status',
    ];
    public function customer() {
        return $this->belongsTo(CustomerModel::class, 'customer_id');
    }

    public function room() {
        return $this->belongsToMany(RoomModel::class, 'booking_room')
                                                    ->withPivot('price')
                                                    ->withTimestamps();
    }
   


}
