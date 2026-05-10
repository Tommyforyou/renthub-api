<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleAvailability extends Model
{
    protected $fillable = [
        'vehicle_id',
        'blocked_from',
        'blocked_until',
        'reason',
        'type',
    ];

    protected $casts = [
        'blocked_from' => 'date',
        'blocked_until' => 'date',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}