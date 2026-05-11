<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Concerns\HasTeams;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

#[Fillable(['name', 'email', 'password', 'current_team_id'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasTeams, Notifiable, TwoFactorAuthenticatable;
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];
   
    public function rentalCompany()
    {
        return $this->hasOne(RentalCompany::class);
    }
   
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isRentalCompany(): bool
    {
        return $this->role === 'rental_company';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }
    /*
    |--------------------------------------------------------------------------
    | Payments Relationship
    |--------------------------------------------------------------------------
    |
    | Customer payments made through the platform.
    |
    */

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
