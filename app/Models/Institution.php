<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    protected $fillable = [
        'name',
        'address',
        'contact_person',
        'contact_phone',
    ];
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
