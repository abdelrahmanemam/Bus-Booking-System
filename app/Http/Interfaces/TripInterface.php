<?php

namespace App\Http\Interfaces;

interface TripInterface
{
    public function getTrip(array $stations, bool $is_main);

    public function getAvailableSeats(array $stations, object $trip, int $max_seat);
}
