<?php

namespace App\Http\Controllers;

use App\Http\Resources\ParkingResource;
use App\Models\Parking;
use App\Services\ParkingPriceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

/**
 * @group Parking
 */
class ParkingController extends Controller
{
    public function index()
    {
        return ParkingResource::collection(Parking::with('vehicle', 'zone')->active()->get());
    }

    public function stoppedParkings()
    {
        return ParkingResource::collection(Parking::with('vehicle', 'zone')->stopped()->get());
    }

    public function start(Request $request): ParkingResource|JsonResponse
    {
        $parkingData = $request->validate([
            'vehicle_id' => [
                'required',
                'integer',
                'exists:vehicles,id,deleted_at,NULL,user_id,' . auth()->id(),
            ],
            'zone_id' => ['required', 'integer', 'exists:zones,id'],
        ]);
        if (Parking::active()->where('vehicle_id', $request->vehicle_id)->exists()) {
            return response()->json([
                'errors' => ['general' => ['Can\'t star parking twice using same vehicle. Please stop currently active parking.']],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $parking = Parking::create($parkingData);
        $parking->load('vehicle', 'zone');

        return ParkingResource::make($parking);
    }

    public function show(Parking $parking): ParkingResource
    {
        return ParkingResource::make($parking);
    }

    public function stop(Parking $parking): ParkingResource
    {
        $parking->update([
            'stop_time' => now(),
            'total_price' => ParkingPriceService::calculatePrice($parking->zone_id, $parking->start_time),
        ]);

        return ParkingResource::make($parking);
    }
}
