<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\HasImages;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens, HasImages;

    /**
     * The default guard name for the model.
     *
     * @var string
     */
    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
        ];
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

     //Accessors 

    //$user->upcoming_reservations  
    public function getUpcomingReservationsAttribute()
    {
        return $this->reservations()
            ->whereHas('event', function ($query) {
                $query->where('start_date', '>', now());
            })
            ->where('status', '!=', 'cancelled')
            ->get();
    }

    //$user->past_reservations  
    public function getPastReservationsAttribute()
    {
        return $this->reservations()
            ->whereHas('event', function ($query) {
                $query->where('end_date', '<', now());
            })
            ->get();
    }

   
}
