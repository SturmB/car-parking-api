<?php

namespace App\Http\Resources;

use App\Models\Parking;
use App\Services\ParkingPriceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Parking */
class ParkingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $totalPrice = $this->total_price ?? ParkingPriceService::calculatePrice(
            $this->zone_id,
            $this->start_time,
            $this->stop_time
        );

        $startDate = $this->start_time;
        $stopDate = $this->stop_time;

        if ($startDate instanceof Carbon) {
            $startDate = $this->start_time->format('Y-m-d H:i:s');
        }

        if ($stopDate instanceof Carbon) {
            $stopDate = $this->stop_time->format('Y-m-d H:i:s');
        }

        return [
            'id' => $this->id,
            'zone' => [
                'name' => $this->zone->name,
                'price_per_hour' => $this->zone->price_per_hour,
            ],
            'vehicle' => [
                'plate_number' => $this->vehicle->plate_number,
            ],
            'start_time' => $startDate,
            'stop_time' => $stopDate,
            'total_price' => $totalPrice,
        ];
    }
}
