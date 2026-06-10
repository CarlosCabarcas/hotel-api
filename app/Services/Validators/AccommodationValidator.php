<?php

namespace App\Services\Validators;

use App\Exceptions\InvalidRoomConfigurationException;

use App\Models\RoomType;

class AccommodationValidator
{
    public function validate(array $configurations): void
    {
        foreach ($configurations as $configuration) {

            $roomType = RoomType::with(
                'accommodations'
            )->findOrFail(
                $configuration['room_type_id']
            );

            $isAllowed =
                $roomType
                    ->accommodations
                    ->contains(
                        'id',
                        $configuration['accommodation_id']
                    );

            if (!$isAllowed) {
                throw new InvalidRoomConfigurationException();
            }
        }
    }
}
