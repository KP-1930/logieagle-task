<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    public $table = 'rooms';
    public $fillable = ['room_no', 'room_type', 'is_bathtub', 'is_balcony', 'is_mini_bar', 'max_occupancy'];

    public static function getRoomType()
    {
        $roomTypes = ['Deluxe', 'Luxury', 'Royal'];
        return $roomTypes;
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
