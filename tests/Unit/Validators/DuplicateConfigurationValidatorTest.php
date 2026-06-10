<?php

namespace Tests\Unit\Validators;

use Tests\TestCase;
use App\Services\Validators\DuplicateConfigurationValidator;


class DuplicateConfigurationValidatorTest extends TestCase
{
    /**
     * Verify that the validation does not throw an exception
     * when all combinations are unique.
     */

    public function test_accepts_unique_configurations(): void
    {
        $validator = new DuplicateConfigurationValidator();

        $configurations = [
            [
                'room_type_id' => 1,
                'accommodation_id' => 1
            ],
            [
                'room_type_id' => 1,
                'accommodation_id' => 2
            ],
            [
                'room_type_id' => 2,
                'accommodation_id' => 3
            ]
        ];

        $validator->validate(
            $configurations
        );

        //The test passes because no exception was thrown.
        $this->expectNotToPerformAssertions();
    }
}
