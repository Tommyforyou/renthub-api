<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\VehicleImage;

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

}