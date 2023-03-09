<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TripStation extends Model
{
    use HasFactory;

    protected $table = 'trip_station';

    protected $fillable = [
        'trip_id',
        'from_station',
        'to_station',
        'num_reservation',
    ];

    public function fromStations(): BelongsTo
    {
        return $this->belongsTo(Station::class, 'from_station');
    }

    public function toStations(): BelongsTo
    {
        return $this->belongsTo(Station::class, 'to_station');
    }

    public function trips(): BelongsTo
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }

}
