<?php

namespace App\Http\Services;

use App\Models\TripStation;

class TripStationService
{
    public static function getTripBetweenEndAndMaxReservations(int $start_station, int $end_station, array $end_stations, $id): bool|int
    {
        $reservations = 0;

        foreach ($end_stations as $end_s) {
            self::createIfNotExist($start_station, $end_s, $id);

            $trip = TripStation::where('trip_id', $id)
                ->where('from_station', $start_station)
                ->where('to_station', $end_s)
                ->first();

            $reservations += $trip->num_reservation;
        }

        return (int)$reservations;
    }

    public static function getTripBetweenStartAndMinReservations(int $start_station, int $end_station, array $end_stations, $id): bool|int
    {
        if (count($end_stations) === 1 && $start_station == $end_stations[0])
            return 0;

        $reservations = 0;
        foreach ($end_stations as $end_s) {
            self::createIfNotExist($end_s, $end_station, $id);

            $trip = TripStation::where('trip_id', $id)
                ->where('from_station', $end_s)
                ->where('to_station', $end_station)
                ->first();

            $reservations += $trip->num_reservation;
        }

        return (int)$reservations;
    }

    public static function getStationsBetweenEndAndMaxToByOrders(int $end_order, mixed $max_to_station_order, $id): array
    {
        $station_ids = [];

        $stations = TripStation::where('trip_id', $id)
            ->with('toStations')
            ->get();

        $orders = range($end_order, $max_to_station_order);

        foreach ($stations as $station) {
            if (in_array($station->toStations->order, $orders))
                $station_ids[$station->toStations->id] = $station->toStations->order;
        }

        return $station_ids;
    }

    public static function getStationsBetweenStartAndMinFromByOrders(int $start_order, mixed $min_from_station_order, $id): array
    {
        $station_ids = [];

        $stations = TripStation::where('trip_id', $id)
            ->with('fromStations')
            ->get();

        $orders = range($min_from_station_order, $start_order);

        foreach ($stations as $station) {
            if (in_array($station->fromStations->order, $orders))
                $station_ids[$station->fromStations->id] = $station->fromStations->order;
        }
        return $station_ids;
    }

    public static function getStationsBetweenStartAndEndByEndOrder(int $start_order, int $end_order, $id): array
    {
        $station_ids = [];

        $stations = TripStation::where('trip_id', $id)
            ->with('toStations')
            ->get();

        $orders = range($start_order, $end_order);

        foreach ($stations as $station) {
            $order = $station->toStations->order;
            if (in_array($station->toStations->order, $orders) && $order != $start_order && $order != $end_order)
                $station_ids[$station->toStations->id] = $station->toStations->order;
        }
        return $station_ids;
    }

    public static function getBetweenTripStationReservations(int $start_station, int $end_station, array $mid_stations, $id): int
    {
        $total_reserve = 0;

        foreach ($mid_stations as $mid_station) {
            self::createIfNotExist($start_station, $mid_station, $id);
            self::createIfNotExist($mid_station, $end_station, $id);

            $total_reserve += TripStation::where('trip_id', $id)
                ->where('from_station', $mid_station)
                ->orWhere('to_station', $mid_station)
                ->sum('num_reservation');
        }
        return (int)$total_reserve;
    }

    public static function createIfNotExist(int $start_station, $end_station, $id)
    {
        TripStation::firstOrCreate([
            'trip_id' => $id,
            'from_station' => $start_station,
            'to_station' => $end_station,
        ]);
    }

    public static function update($trip, $stations, $num_seats): bool
    {
        $trip_station = TripStation::where('trip_id', $trip->id)
            ->where('from_station', $stations['start_station'])
            ->where('to_station', $stations['end_station'])
            ->first();

        return $trip_station->update(['num_reservation' => $num_seats + $trip_station->first()->num_reservation]);
    }
}
