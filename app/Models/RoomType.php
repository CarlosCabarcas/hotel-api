<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    protected $fillable = [
        'name',
    ];

    public function accommodations()
    {
        return $this->belongsToMany(Accommodation::class, 'accommodation_room_type');
    }
}
