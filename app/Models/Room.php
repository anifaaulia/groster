<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
  protected $fillable = [
    'name',
    'institution_id',
    'capacity',
    'status',
  ];
  public function institution()
  {
    return $this->belongsTo(Institution::class);
  }
  public function facilities()
  {
    return $this->belongsToMany(Facility::class, 'room_facilities')->withPivot('quantity');
  }
  public function attendances()
  {
    return $this->hasMany(Attendance::class);
  }
  public function room_booking()
  {
    return $this->hasMany(RoomBooking::class);
  }
}
