<?php

namespace App\Http\Controllers\Api;

use App\Models\Hotel;
use App\Services\HotelService;
use App\Http\Controllers\Controller;
use App\Http\Resources\HotelResource;
use App\Http\Requests\StoreHotelRequest;
use App\Http\Requests\UpdateHotelRequest;

class HotelController extends Controller
{
    /**
     * service that contains
     * the business logic for hotels.
     */
    public function __construct(private HotelService $hotelService)
    {
    }

    /**
     * Returl all hotels
     */
    public function index()
    {
        $hotels = Hotel::query()
            ->latest() //sort from newest to oldest
            ->select([
                'id',
                'name',
                'city',
                'nit',
                'total_rooms'
            ])
            ->paginate(10);

        return HotelResource::collection($hotels);
    }

    /**
     * Return detail from a hotel
     */
    public function show(Hotel $hotel)
    {
        $hotel->load([
            'configurations.roomType',
            'configurations.accommodation'
        ]);

        return new HotelResource($hotel);
    }

    /**
     * Create new hotel with configurations
     */
    public function store(StoreHotelRequest $request)
    {
        $hotel = $this->hotelService->create($request->validated());

        return response()->json([
            'message' => 'Hotel creado correctamente.',
            'data' => new HotelResource($hotel)
        ], 201);
    }

    /**
     * Update hotel and configurations.
     */
    public function update(UpdateHotelRequest $request, Hotel $hotel)
    {
        $hotel = $this->hotelService->update($hotel, $request->validated());

        return response()->json([
            'message' => 'Hotel actualizado correctamente.',
            'data' => new HotelResource($hotel)
        ]);
    }

    /**
     * Delete hotel with softDeletes
     */
    public function destroy(Hotel $hotel)
    {
        $hotel->delete();

        return response()->json([
            'message' => 'Hotel eliminado correctamente.'
        ]);
    }
}
