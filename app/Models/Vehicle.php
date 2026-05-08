<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_company_id',
        'title',
        'brand',
        'model',
        'year',
        'transmission',
        'fuel_type',
        'seats',
        'price_per_day',
        'available',
        'description',
        'image',
    ];

    public function rentalCompany()
    {
        return $this->belongsTo(RentalCompany::class);
    }
}