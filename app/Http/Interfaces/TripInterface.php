<?php

namespace App\Http\Interfaces;

interface TripInterface
{
    public function getTripWhenStartStationIsMain(int $start_station);

    public function getTripWhenStartStationIsNotMain(int $start_station, int $main_station);

    public function getAvailableSeats(array $stations, object $trip, int $max_seat, int $start_order, int $end_order);
}
