<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Station extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'main_station',
        'order'
    ];

    public function mainStation(): BelongsTo
    {
        return $this->belongsTo(Station::class, 'main_station');
    }
}
