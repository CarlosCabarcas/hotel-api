<?php

namespace App\Exceptions;

use Exception;

class InvalidRoomConfigurationException extends Exception
{
    /**
     * This error occurs when an accommodation
     * does not match the room type.
     */
    protected $message =
        'La acomodación seleccionada no es válida para el tipo de habitación.';
}
