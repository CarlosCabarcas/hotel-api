<?php

namespace App\Services;

use  App\Models\Hotel;
use App\Models\RoomType;
use Illuminate\Support\Facades\DB;
use App\Exceptions\DuplicateRoomConfigurationException;
use App\Exceptions\RoomLimitExceededException;
use App\Exceptions\InvalidRoomConfigurationException;


class HotelService
{
    /**
     * Create a hotel with its corresponding configurations.
     */
    public function create(array $data): Hotel
    {
        $this->validateBusinessRules($data);

        return DB::transaction(function () use ($data){
            //create hotel
            $hotel = Hotel::create([
                'name' => $data['name'],
                'city' => $data['city'],
                'address' => $data['address'],
                'nit' => $data['nit'],
                'total_rooms' => $data['total_rooms']
            ]);

            //create configurations for the hotel
            $this->saveConfigurations($hotel, $data['configurations']);

            return $hotel->load([
                'configurations.roomType',
                'configurations.accommodation'
            ]);
        });
    }

    public function update(Hotel $hotel, array $data): Hotel
    {
        $this->validateBusinessRules($data);

        return DB::transaction(function () use ($hotel,$data){
            $hotel->update([
                'name' => $data['name'],
                'city' => $data['city'],
                'address' => $data['address'],
                'nit' => $data['nit'],
                'total_rooms' => $data['total_rooms']
            ]);

            //delete the current configurations
            $hotel->configurations()->delete();

            //insert new configurations
            $this->saveConfigurations($hotel, $data['configurations']);
        });
    }

    /**
     * Verify that there are no duplicate combinations
     * in the request.
     */
    private function validateDuplicates(array $configurations): void
    {
        $combinations = [];

        foreach ($configurations as $configuration) {

            $key =
                $configuration['room_type_id']
                . '-'
                . $configuration['accommodation_id'];

            if (in_array($key, $combinations)) {
                throw new DuplicateRoomConfigurationException();
            }

            $combinations[] = $key;
        }
    }

    /**
     * Verify that the total number of rooms
     * does not exceed the limit set for the hotel.
     */
    private function validateRoomLimit(int $totalRooms, array $configurations): void
    {
        $configuredRooms = collect($configurations)
            ->sum('quantity');

        if ($configuredRooms > $totalRooms) {
            throw new RoomLimitExceededException();
        }
    }

    /**
     * Verify that the accommodation corresponds
     * to the selected room type.
     */
    private function validateAccommodationRules(array $configurations): void
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

    private function validateBusinessRules(array $data): void
    {
        $this->validateDuplicates(
            $data['configurations']
        );

        $this->validateAccommodationRules(
            $data['configurations']
        );

        $this->validateRoomLimit(
            $data['total_rooms'],
            $data['configurations']
        );
    }

    private function saveConfigurations(Hotel $hotel, array $configurations): void
    {
        foreach ($configurations as $configuration) {

            $hotel->configurations()->create([
                'room_type_id' => $configuration['room_type_id'],
                'accommodation_id' => $configuration['accommodation_id'],
                'quantity' => $configuration['quantity']
            ]);
        }
    }
}
