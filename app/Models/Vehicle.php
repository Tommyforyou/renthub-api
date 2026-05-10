<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\VehicleImage;
use App\Models\RentalCompany;

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
        'daily_price',
        'weekly_discount',
        'monthly_discount',
        'weekend_multiplier',
        'security_deposit',
        'delivery_fee',
        'minimum_days',
        'maximum_days',
        'instant_booking',
    ];

    protected $casts = [
        'instant_booking' => 'boolean',
    ];

    public function rentalCompany()
    {
        return $this->belongsTo(RentalCompany::class);
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function images()
    {
        return $this->hasMany(VehicleImage::class)
            ->orderBy('sort_order');
    }

    public function seasonalPrices()
    {
        return $this->hasMany(SeasonalPrice::class);
    }

    public function availabilityBlocks()
    {
        return $this->hasMany(VehicleAvailability::class);
    }
    public function company()
    {
        return $this->belongsTo(\App\Models\RentalCompany::class, 'rental_company_id');
    }
}