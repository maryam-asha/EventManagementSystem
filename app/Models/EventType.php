<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($eventType) {
            if (empty($eventType->slug)) {
                $eventType->slug = \Str::slug($eventType->name);
            }
        });
    }
}
