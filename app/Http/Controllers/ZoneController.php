<?php

namespace App\Http\Controllers;

use App\Http\Resources\ZoneResource;
use App\Models\Zone;

class ZoneController extends Controller
{
    public function index()
    {
        return ZoneResource::collection(Zone::all());
    }
}
