<?php

namespace Tests\Unit\Validators;

use Tests\TestCase;
use App\Services\Validators\RoomLimitValidator;

class RoomLimitValidatorTest extends TestCase
{
    /**
     * The total number of rooms
     * is within the permitted limit.
     */
    public function test_accepts_valid_room_limit(): void
    {
        $validator =
            new RoomLimitValidator();

        $configurations = [
            [
                'quantity' => 10
            ],
            [
                'quantity' => 15
            ]
        ];

        $validator->validate(
            30,
            $configurations
        );

        $this->expectNotToPerformAssertions();
    }
}
