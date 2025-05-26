<?php

namespace App\Models;

use App\Traits\HasImages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasImages;

    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'capacity',
        'description',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'capacity' => 'integer',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    //Accessors 
    //$location->full_address 
    public function getFullAddressAttribute(): string
    {
        return collect([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ])->filter()->join(', ');
    }
}
