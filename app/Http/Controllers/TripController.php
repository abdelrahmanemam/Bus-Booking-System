<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\TripInterface;
use App\Http\Repositories\TripRepository;
use App\Http\Services\StationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TripController extends Controller
{
    private TripInterface $tripInterface;

    private int $max_seat = 12;

    public function __construct(TripInterface $tripInterface)
    {
        $this->tripInterface = $tripInterface;
    }

    public function seats(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_station' => 'string|required',
            'end_station' => 'string|required',
        ]);

        if ($validator->fails())
            return response()->json($validator->errors(), ResponseAlias::HTTP_FORBIDDEN);


        $stations = StationService::getStations($request->toArray());

        if (!$stations)
            return response()->json('Provided stations does\'t exist', ResponseAlias::HTTP_FORBIDDEN);

        $start_is_main_station = StationService::startIsMainStation($stations['start_station']);

        $stations_in_trip = StationService::checkStationsInTrip($stations, $start_is_main_station);

        $trip = $this->tripInterface->getTrip($stations, $start_is_main_station);

        if (!$stations_in_trip || !$trip)
            return response()->json('Provided stations does\'t have trips', ResponseAlias::HTTP_FORBIDDEN);


        $seats = $this->tripInterface->getAvailableSeats($stations, $trip, $this->max_seat);

        if (!$seats)
            return response()->json('No available trips for the provided stations', ResponseAlias::HTTP_NOT_FOUND);

        return response(['Available seats' => $seats], ResponseAlias::HTTP_OK);

    }
}
