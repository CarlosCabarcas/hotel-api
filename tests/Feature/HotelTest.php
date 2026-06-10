<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Hotel;

class HotelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    /**
     * Verify that a hotel can
     * be created successfully.
     */
    public function test_can_create_hotel(): void
    {
        $payload = [
            'name' => 'Decameron',
            'city' => 'Cartagena',
            'address' => 'Centro',
            'nit' => '123456',
            'total_rooms' => 42,
            'configurations' => [
                [
                    'room_type_id' => 1,
                    'accommodation_id' => 1,
                    'quantity' => 25
                ],
                [
                    'room_type_id' => 2,
                    'accommodation_id' => 3,
                    'quantity' => 17
                ]
            ]
        ];

        $response = $this->postJson(
            '/api/hotels',
            $payload
        );

        $response->assertCreated();

        $this->assertDatabaseHas(
            'hotels',
            [
                'nit' => '123456'
            ]
        );

        $this->assertDatabaseCount(
            'hotel_room_configurations',
            2
        );
    }

    /**
     * Verify that no hotels are created
     * with duplicate NIT.
     */
    public function test_cannot_create_duplicate_nit(): void
    {
        Hotel::factory()->create([
            'nit' => '123456'
        ]);

        $payload = [
            'name' => 'Hotel Nuevo',
            'city' => 'Bogotá',
            'address' => 'Centro',
            'nit' => '123456',
            'total_rooms' => 10,
            'configurations' => [
                [
                    'room_type_id' => 1,
                    'accommodation_id' => 1,
                    'quantity' => 10
                ]
            ]
        ];

        $response = $this->postJson(
            '/api/hotels',
            $payload
        );

        $response->assertStatus(422);
    }

    /**
     * Verify that no hotels are created
     * with duplicate configurations.
     */
    public function test_cannot_create_duplicate_configurations(): void
    {
        $payload = [
            'name' => 'Hotel',
            'city' => 'Cartagena',
            'address' => 'Centro',
            'nit' => '123456',
            'total_rooms' => 20,
            'configurations' => [
                [
                    'room_type_id' => 1,
                    'accommodation_id' => 1,
                    'quantity' => 10
                ],
                [
                    'room_type_id' => 1,
                    'accommodation_id' => 1,
                    'quantity' => 5
                ]
            ]
        ];

        $response = $this->postJson(
            '/api/hotels',
            $payload
        );

        $response->assertStatus(422);

        $response->assertJson([ 'message' => 'Existen configuraciones duplicadas para el mismo tipo y acomodación.']);
    }

    /**
     * Verify that no hotels are created
     * when the maximum number of rooms is exceeded.
     */
    public function test_cannot_exceed_room_limit(): void
    {
        $payload = [
            'name' => 'Hotel',
            'city' => 'Cartagena',
            'address' => 'Centro',
            'nit' => '123456',
            'total_rooms' => 10,
            'configurations' => [
                [
                    'room_type_id' => 1,
                    'accommodation_id' => 1,
                    'quantity' => 8
                ],
                [
                    'room_type_id' => 2,
                    'accommodation_id' => 3,
                    'quantity' => 8
                ]
            ]
        ];

        $response = $this->postJson(
            '/api/hotels',
            $payload
        );

        $response->assertStatus(422);
    }

    /**
     * Verify that no hotels are created
     * with invalid accommodation
     */
    public function test_cannot_use_invalid_accommodation(): void
    {
        $payload = [
            'name' => 'Hotel',
            'city' => 'Cartagena',
            'address' => 'Centro',
            'nit' => '123456',
            'total_rooms' => 10,
            'configurations' => [
                [
                    'room_type_id' => 2,
                    'accommodation_id' => 1,
                    'quantity' => 10
                ]
            ]
        ];

        $response = $this->postJson(
            '/api/hotels',
            $payload
        );

        $response->assertStatus(422);
    }
}
