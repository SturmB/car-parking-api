<?php

namespace App\Services;

use App\Models\Zone;
use Carbon\Carbon;

class ParkingPriceService
{
    public static function calculatePrice(int $zone_id, string $startTime, string $stopTime = null): int
    {
        $start = new Carbon($startTime);
        $stop = !is_null($stopTime) ? new Carbon($stopTime) : now();

        $totalTimeInMinutes = $stop->diffInMinutes($start);

        $pricePerMinute = Zone::find($zone_id)->price_per_hour / 60;

        return ceil($totalTimeInMinutes * $pricePerMinute);
    }
}
