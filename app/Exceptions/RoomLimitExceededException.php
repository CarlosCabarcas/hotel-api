<?php

namespace App\Exceptions;
use Exception;

class RoomLimitExceededException extends Exception
{
    /**
     * This error occurs when the total number of room configurations
     * exceeds the total number of rooms in the hotel.
     */

    protected $message = 'La suma de habitaciones configuradas excede el total permitido.';
}
