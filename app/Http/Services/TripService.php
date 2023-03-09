<?php

namespace App\Http\Services;
class TripService
{

    public static function getMaxOrder(object $trip_array, string $string)
    {
        return max(array_column($trip_array->toArray(), $string));
    }

    public static function getMinOrder(object $trip_array, string $string)
    {
        return min(array_column($trip_array->toArray(), $string));
    }

}
