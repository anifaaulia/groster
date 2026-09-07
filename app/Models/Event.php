<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'start_date',
        'end_date',
        'is_approved',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function room_bookings()
    {
        return $this->hasMany(RoomBooking::class);
    }
    public function participants()
    {
        return $this->belongsToMany(User::class, 'event_user');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
