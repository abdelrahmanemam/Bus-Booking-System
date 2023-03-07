<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TripStation extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'from_station',
        'to_station',
        'num_reservation',
    ];

    public function fromStations(): BelongsToMany
    {
        return $this->belongsToMany(Station::class,'trip_station','from_station', 'trip_id');
    }

    public function toStations(): BelongsToMany
    {
        return $this->belongsToMany(Station::class,'trip_station','to_station', 'trip_id');
    }

}
