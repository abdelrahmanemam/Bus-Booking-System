<?php

namespace App\Http\Repositories;


use App\Http\Interfaces\TripInterface;
use App\Http\Services\StationService;
use App\Http\Services\TripService;
use App\Http\Services\TripStationService;
use App\Models\Trip;
use App\Models\TripStation;

class TripRepository implements TripInterface
{
    public function getAvailableSeats(array $stations, object $trip, int $max_seat): bool|int
    {
        $available_seats = $max_seat;

        $start_order = (int)StationService::getStationOrder($stations['start_station']);
        $end_order = (int)StationService::getStationOrder($stations['end_station']);

        $trip_details = $this->getTripDetailsWithStationOrder($trip->id);

        $min_from_station_order = TripService::getMinOrder($trip_details, 'from_station_order');
        $max_to_station_order = TripService::getMaxOrder($trip_details, 'to_station_order');

        $is_direct = $end_order - $start_order === 1;

        if (!$is_direct) {
            $between_station = TripStationService::getStationsBetweenStartAndEndByEndOrder($start_order, $end_order, $trip->id); // Get stations between start_station and end_station
            $reservations_between = TripStationService::getBetweenTripStationReservations((int)$stations['start_station'], (int)$stations['end_station'], array_keys($between_station), $trip->id); // Get reservations for mid_stations to end_station

            if ($reservations_between === $max_seat)
                return false;

            $available_seats -= $reservations_between;
        }

        $to_station_ids = TripStationService::getStationsBetweenEndAndMaxToByOrders($end_order, $max_to_station_order, $trip->id); // Get stations between to_station and last_station
        $reservations_to = TripStationService::getTripBetweenEndAndMaxReservations($stations['start_station'], $stations['end_station'], array_keys($to_station_ids), $trip->id); // Get reservations for every start_station to previous stations

        $from_station_ids = TripStationService::getStationsBetweenStartAndMinFromByOrders($start_order, $min_from_station_order, $trip->id); // Get stations between from_station and first_station
        $reservations_from = TripStationService::getTripBetweenStartAndMinReservations($stations['start_station'], $stations['end_station'], array_keys($from_station_ids), $trip->id); // Get reservations for every previous stations to start_station

        if ($reservations_to === $max_seat || $reservations_from === $max_seat)
            return false;

        $available_seats -= $reservations_to + $reservations_from;

        return $available_seats;
    }

    public function getTrip(array $stations, bool $is_main): false|object
    {
        if ($is_main) {
            $trip = Trip::where('start_station', $stations['start_station'])
                ->first();

        } else {
            $main_station = StationService::getMainStationOfStartStation($stations);

            $trip = Trip::where('start_station', $main_station)
                ->first();

        }

        return $trip ?? false;
    }

    public function getTripDetailsWithStationOrder($trip_id): object
    {
        return TripStation::where('trip_id', $trip_id)
            ->with(['fromStations', 'toStations'])
            ->get()
            ->sortBy(function ($q) {
                return $q->fromStations->order;
            })
            ->map(function ($v) {
                return [
                    'from_station' => $v->from_station,
                    'from_station_order' => $v->fromStations->order ?? null,
                    'to_station' => $v->to_station,
                    'to_station_order' => $v->toStations->order ?? null,
                    'reservations' => $v->num_reservation ?? null,
                ];
            });
    }
}
