<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $fillable = [
        'name',
        'city',
        'address',
        'nit',
        'total_rooms',
    ];

    public function configuration()
    {
        return $this->hasMany(HotelRoomConfiguration::class);
    }
}
