<?php

namespace App\Services\Validators;

use App\Exceptions\DuplicateRoomConfigurationException;

class DuplicateConfigurationValidator
{
    /**
     * Validate that there are no duplicate room configurations.
     *
     * @param array $configurations
     * @throws DuplicateRoomConfigurationException
     */
    public function validate(array $configurations): void
    {
        $seenConfigurations = [];

        foreach ($configurations as $configuration) {
            $key = $configuration['room_type_id'] . '-' . $configuration['accommodation_id'];

            if (isset($seenConfigurations[$key])) {
                throw new DuplicateRoomConfigurationException();
            }

            $seenConfigurations[$key] = true;
        }
    }
}
