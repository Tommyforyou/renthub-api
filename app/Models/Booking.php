<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'customer_id',
        'start_date',
        'end_date',
        'total_days',
        'daily_rate',
        'subtotal',
        'commission_amount',
        'owner_payout_amount',
        'total_amount',
        'status',
        'payment_status',
        'deposit_amount',
        'remaining_balance',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}