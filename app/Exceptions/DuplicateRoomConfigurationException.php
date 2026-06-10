<?php

namespace App\Exceptions;

use Exception;

class DuplicateRoomConfigurationException extends Exception
{
    /**
     * It is triggered when
     * a type-and-placement combination is repeated.
     */
    protected $message = 'Existen configuraciones duplicadas para el mismo tipo y acomodación.';
}
