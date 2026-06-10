<?php

namespace App\Http\Controllers\Api;


use App\Models\RoomType;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoomTypeResource;

class RoomTypeController extends Controller
{
    /**
     * Returns room types
     * along with their permitted occupancy.
     */
    public function index()
    {
        $roomTypes = RoomType::query()
            ->with('accommodations')
            ->get();

        return RoomTypeResource::collection(
            $roomTypes
        );
    }
}
