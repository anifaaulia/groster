<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'room_id',
        'event_id',
        'room_booking_id',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function roomBooking()
    {
        return $this->belongsTo(RoomBooking::class);
    }
}
