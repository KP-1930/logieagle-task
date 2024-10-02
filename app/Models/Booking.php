<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    public $table = 'bookings';
    public $fillable = ['user_id', 'room_id', 'start_date', 'end_date', 'total_cost'];

    public function getUserName()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getRoomId()
    {
        return $this->hasOne(Room::class, 'id', 'room_id');
    }
}
