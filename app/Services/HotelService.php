<?php

namespace App\Services;

use  App\Models\Hotel;
use App\Models\RoomType;
use Illuminate\Support\Facades\DB;
use App\Services\Validators\DuplicateConfigurationValidator;
use App\Services\Validators\RoomLimitValidator;
use App\Services\Validators\AccommodationValidator;
use App\Exceptions\RoomLimitExceededException;
use App\Exceptions\InvalidRoomConfigurationException;


class HotelService
{
    public function __construct(
        private DuplicateConfigurationValidator $duplicateValidator,
        private RoomLimitValidator $roomLimitValidator,
        private AccommodationValidator $accommodationValidator
    ) {
    }

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

    private function validateBusinessRules(array $data): void
    {
        $this->duplicateValidator->validate($data['configurations']);
        $this->accommodationValidator->validate($data['configurations']);
        $this->roomLimitValidator->validate($data['total_rooms'], $data['configurations']);
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
