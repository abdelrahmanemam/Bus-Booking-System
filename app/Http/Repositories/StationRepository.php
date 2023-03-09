<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\StationInterface;
use App\Models\Station;

class StationRepository implements StationInterface
{

    public function getStationOnName($station_name): object|null
    {
        return Station::where('name', 'like', '%' . $station_name . '%')
            ->first();
    }

    public function getStationOnEndAndStartNames(string $end_station, string $start_station)
    {
        return Station::where('id', $end_station)
            ->where('main_station', $start_station)
            ->first();
    }

    public function getMainStationOnStationId(int $id): int
    {
        return (int)Station::where('id', $id)
            ->first()
            ->main_station;
    }

    public function checkStartStationIsMain(int $station_id): bool
    {
        return (bool)Station::where('id', $station_id)
            ->whereNull('main_station')
            ->first();
    }


    public function getStationOnId(int $station_id): object
    {
        return Station::where('id', $station_id)
            ->first();
    }
}
