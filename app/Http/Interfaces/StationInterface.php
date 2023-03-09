<?php

namespace App\Http\Interfaces;

interface StationInterface
{
    public function getStationOnName($station_name): object|null;

    public function getStationOnEndAndStartNames(string $end_station, string $start_station);

    public function getMainStationOnStationId(int $id);

    public function checkStartStationIsMain(int $station_id): bool;

    public function getStationOnId(int $station_id): object;

}
