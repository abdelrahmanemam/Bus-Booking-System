<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserTrip extends Model
{
    use HasFactory;

    protected $table = 'user_trip';

    protected $fillable = [
        'user_id',
        'trip_id',
        'seat_number'
    ];

    public function trips(): BelongsToMany
    {
        return $this->belongsToMany(Trip::class, 'user_trip');
    }
}
