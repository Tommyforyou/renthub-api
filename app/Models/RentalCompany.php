<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'brn',
        'vat_number',
        'contact_person',
        'phone',
        'email',
        'address',
        'status',
        'commission_rate',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
    /*
    |--------------------------------------------------------------------------
    | Payments Relationship
    |--------------------------------------------------------------------------
    |
    | Payments linked to this rental company.
    |
    */

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

}
