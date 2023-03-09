<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\StationInterface;
use App\Http\Interfaces\TripInterface;
use App\Http\Repositories\StationRepository;
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
    private StationController $stationController;

    private int $max_seat = 12;

    public function __construct(TripInterface $tripInterface)
    {
        $this->tripInterface = $tripInterface;
        $this->stationController = new StationController(new StationRepository());
    }

    public function seats(Request $request): JsonResponse
    {
        $stations = $this->stationsValidate($request);

        if (gettype($stations) === "object")
            return $stations;

        $trip = $this->tripValidate($stations);
        if (!$trip)
            return response()->json('Provided stations does\'t have trips', ResponseAlias::HTTP_FORBIDDEN);

        TripStationService::createIfNotExist($stations['start_station'], $stations['end_station'], $trip->id);

        $start_order = $this->stationController->getStationOrder((int)$stations['start_station']);
        $end_order = $this->stationController->getStationOrder((int)$stations['end_station']);

        $seats = $this->tripInterface->getAvailableSeats($stations, $trip, $this->max_seat, $start_order, $end_order);

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

        $stations = $this->stationController->getStations($request->toArray());
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


        $stations = $this->stationController->getStations($request->toArray());

        if (!$stations)
            return response()->json('Provided stations does\'t exist', ResponseAlias::HTTP_FORBIDDEN);

        return $stations;
    }

    public function tripValidate(array $stations)
    {
        $start_is_main_station = $this->stationController->startIsMainStation((int)$stations['start_station']);

        $stations_in_trip = $this->stationController->checkStationsInTrip($stations, $start_is_main_station);

        if ($start_is_main_station)
            $trip = $this->tripInterface->getTripWhenStartStationIsMain((int)$stations['start_station']);
        else {
            $main_station = $this->stationController->getMainStationOfStartStation((int)$stations['start_station']);

            $trip = $this->tripInterface->getTripWhenStartStationIsNotMain((int)$stations['start_station'], $main_station);
        }

        if (!$stations_in_trip || !$trip)
            return false;

        return $trip;
    }
}
