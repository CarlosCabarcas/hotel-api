<?php

namespace App\Services\Validators;

use App\Exceptions\RoomLimitExceededException;

class RoomLimitValidator
{
    /**
     * Verify that the total number of rooms does not exceed the limit set for the hotel.
     *
     * @param array $configurations
     * @throws RoomLimitExceededException
     */

    public function validate(int $totalRooms, array $configurations): void
    {
        $configuredRooms = collect($configurations)
            ->sum('quantity');

        if ($configuredRooms > $totalRooms) {
            throw new RoomLimitExceededException();
        }
    }
}
