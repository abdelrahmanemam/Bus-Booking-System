<?php

namespace App\Http\Services;
use App\Models\UserTrip;

class UserTripService
{
    public static function create($trip_id, $user_id): int
    {
        $seat_number = SeatService::getAvailableSeatNumber();

        $created = UserTrip::create([
            'user_id' => $user_id,
            'trip_id' => $trip_id,
            'seat_number' => $seat_number
        ]);

        if ($created)
            SeatService::makeSeatBusy($seat_number);

        return $seat_number;
    }
}
