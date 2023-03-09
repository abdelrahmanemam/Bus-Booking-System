<?php

namespace App\Http\Services;

use App\Models\Seat;

class SeatService
{
    public static function getAvailableSeatNumber(): int
    {
        $seat = Seat::where('status', 0)
            ->first();

        if (!$seat) {
            self::revokeSeats();
            self::getAvailableSeatNumber();
            return 0;
        } else {
            return $seat->number;
        }
    }

    public static function makeSeatBusy(int $seat_number): void
    {
        Seat::where('number', $seat_number)
            ->update(['status' => 1]);
    }

    private static function revokeSeats(): void
    {
        Seat::where('status', 1)
            ->update(['status' => 0]);
    }
}
