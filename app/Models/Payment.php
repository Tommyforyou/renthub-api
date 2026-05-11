<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Mass Assignable Fields
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'booking_id',
        'customer_id',
        'rental_company_id',
        'amount',
        'commission_amount',
        'company_amount',
        'payment_method',
        'payment_gateway',
        'transaction_reference',
        'status',
        'notes',
        'paid_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'company_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Booking Relationship
    |--------------------------------------------------------------------------
    */

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Customer Relationship
    |--------------------------------------------------------------------------
    */

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Rental Company Relationship
    |--------------------------------------------------------------------------
    */

    public function rentalCompany()
    {
        return $this->belongsTo(RentalCompany::class);
    }
}