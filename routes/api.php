<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\RoomTypeController;

Route::get(
    '/room-types',
    [RoomTypeController::class, 'index']
);

Route::apiResource(
    'hotels',
    HotelController::class
);
