<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\TripInterface;
use App\Http\Services\StationService;
use App\Http\Services\TripStationService;
use App\Http\Services\UserTripService;
use Illuminate\Http\JsonResponse;
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

    public function seats(Request $request): JsonResponse
    {
        $stations = $this->stationsValidate($request);

        $trip = $this->tripValidate($stations);

        TripStationService::createIfNotExist($stations['start_station'], $stations['end_station'], $trip->id);

        $seats = $this->tripInterface->getAvailableSeats($stations, $trip, $this->max_seat);

        if (!$seats)
            return response()->json('No available seats for the provided stations', ResponseAlias::HTTP_NOT_FOUND);

        return response()->json(['Available seats' => $seats], ResponseAlias::HTTP_OK);
    }

    public function bookSeat(Request $request)
    {
        if (!isset($request->num_seats) || !is_int((int)$request->num_seats))
            return response()->json("Please entre number of seats", ResponseAlias::HTTP_FORBIDDEN);

        $available_seats = $this->seats($request)->getData("Available seats");

        if (!is_array($available_seats))
            return response()->json("There are no seats available", ResponseAlias::HTTP_FORBIDDEN);

        if ($request->num_seats > current($available_seats))
            return response()->json("There are only " . current($available_seats) . " seats available. Enter valid number", ResponseAlias::HTTP_FORBIDDEN);

        $stations = StationService::getStations($request);

        $trip = $this->tripValidate($stations);

        $updated = TripStationService::update($trip, $stations, $request->num_seats);

        $seat = UserTripService::create($trip->id, auth('api')->user()->id);

        return ($updated && $seat) ?
            response()->json("Your seats reserved successfully and your seat number is $seat", ResponseAlias::HTTP_OK)
            :
            response()->json("Error occurred, try again", ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function stationsValidate(Request $request): JsonResponse|bool|array
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

        return $stations;
    }

    public function tripValidate(array $stations)
    {
        $start_is_main_station = StationService::startIsMainStation($stations['start_station']);

        $stations_in_trip = StationService::checkStationsInTrip($stations, $start_is_main_station);

        $trip = $this->tripInterface->getTrip($stations, $start_is_main_station);

        if (!$stations_in_trip || !$trip)
            return response()->json('Provided stations does\'t have trips', ResponseAlias::HTTP_FORBIDDEN);

        return $trip;
    }
}
