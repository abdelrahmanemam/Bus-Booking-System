<?php

namespace App\Http\Services;

use App\Models\Station;

class StationService
{

    public static function getStations($request): array|bool
    {
        $start_station = Station::where('name', 'like', '%' . $request['start_station'] . '%')
            ->first();

        $end_station = Station::where('name', 'like', '%' . $request['end_station'] . '%')
            ->first();

        if (!$start_station || !$end_station)
            return false;

        return ['start_station' => $start_station->id, 'end_station' => $end_station->id];
    }

    public static function checkStationsInTrip(array $stations, bool $is_main): bool
    {
        if ($is_main) {
            $trip_exist = Station::where('id', $stations['end_station'])
                ->where('main_station', $stations['start_station'])
                ->first();

            return (bool)$trip_exist;

        } else {
            $start_main = Station::where('id', $stations['start_station'])
                ->first()
                ->main_station;

            $end_main = Station::where('id', $stations['end_station'])
                ->first()
                ->main_station;

            return $start_main === $end_main;
        }

    }

    public static function startIsMainStation(int|string $start_station): bool
    {
        $is_main = Station::where('id', $start_station)
            ->whereNull('main_station')
            ->first();

        return (bool)$is_main;

    }

    public static function getMainStationOfStartStation(array $stations): int|bool
    {
        $station = Station::where('id', $stations['start_station'])
            ->first();

        return $station ? (int)$station->main_station : false;
    }

    public static function getStationOrder($station_id): bool|int
    {
        $station = Station::where('id', $station_id)
            ->first();

        return $station ? (int)$station->order : false;

    }
}
