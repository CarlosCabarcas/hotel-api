<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelRoomConfiguration extends Model
{
    protected $fillable = [
        'hotel_id',
        'accommodation_id',
        'room_type_id',
        'quantity',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
}
