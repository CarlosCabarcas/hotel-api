<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'city',
        'address',
        'nit',
        'total_rooms',
    ];

    public function configurations()
    {
        return $this->hasMany(HotelRoomConfiguration::class);
    }
}
