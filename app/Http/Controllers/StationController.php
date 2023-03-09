<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\StationInterface;
use Illuminate\Http\Request;

class StationController extends Controller
{
    private StationInterface $stationInterface;

    public function __construct(StationInterface $stationInterface)
    {
        $this->stationInterface = $stationInterface;
    }

    public function getStations(array $station_data): bool|array
    {
        $start_station = $this->stationInterface->getStationOnName($station_data['start_station']);

        $end_station = $this->stationInterface->getStationOnName($station_data['end_station']);

        if (!$start_station || !$end_station)
            return false;

        return ['start_station' => $start_station->id, 'end_station' => $end_station->id];

    }

    public function checkStationsInTrip(array $stations, bool $is_main): bool
    {
        if ($is_main) {
            return (bool)$this->stationInterface->getStationOnEndAndStartNames($stations['end_station'], $stations['start_station']);

        } else {
            $start_main = $this->stationInterface->getMainStationOnStationId((int)$stations['start_station']);

            $end_main = $this->stationInterface->getMainStationOnStationId((int)$stations['end_station']);

            return $start_main === $end_main;
        }
    }

    public function startIsMainStation(int $station_id): bool
    {
        return $this->stationInterface->checkStartStationIsMain($station_id);
    }

    public function getMainStationOfStartStation($start_station_id): bool|int
    {
        $station = $this->stationInterface->getStationOnId($start_station_id);

        return $station ? (int)$station->main_station : false;
    }

    public function getStationOrder($station_id): bool|int
    {
        $station = $this->stationInterface->getStationOnId($station_id);

        return $station ? (int)$station->order : false;

    }
}
