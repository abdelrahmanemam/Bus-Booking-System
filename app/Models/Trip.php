<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_station',
        'end_station',
        'bus_id',
    ];

    public function startStation(): BelongsTo
    {
        return $this->belongsTo(Station::class, 'start_station');
    }

    public function endStation(): BelongsTo
    {
        return $this->belongsTo(Station::class, 'end_station');
    }

    public function tripStation(): HasMany
    {
        return $this->HasMany(TripStation::class);
    }

    public function Bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }
}
