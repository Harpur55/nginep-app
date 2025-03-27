<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerModel extends Model
{
    protected $table = 'customer';
    protected $fillable = [
        'user_id','name', 'phone', 'address', 'birth_date', 'gender', 'nationality', 'photo'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function booking() {
        return $this->hasMany(BookingModel::class);
    }

    public function room() {
        return $this->hasMany(RoomModel::class);
    }

  
}
