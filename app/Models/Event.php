<?php

namespace App\Models;

use App\Traits\HasImages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Event extends Model
{
    use SoftDeletes, HasImages;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'event_type_id',
        'location_id',
        'user_id',
        'start_date',
        'end_date',
        'price',
        'capacity',
        'is_published',
        'is_featured',
        'status',
    ];


    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'price' => 'decimal:2',
        'capacity' => 'integer',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function eventType(): BelongsTo
    {
        return $this->belongsTo(EventType::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
    // Mutator for title
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucfirst($value);
        $this->attributes['slug'] = Str::slug($value);
    }

    //Scopes 
    // Event::published()->get(); 
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    // Event::featured()->get(); 
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Event::upcoming()->get(); 
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }
    // Event::past()->get(); 
    public function scopePast($query)
    {
        return $query->where('end_date', '<', now());
    }

    
    
    //Accessors 

    //$event->remaining_capacity   
    public function getRemainingCapacityAttribute(): int
    {
        $reserved = $this->reservations()
            ->where('status', '!=', 'cancelled')
            ->sum('quantity');
        
        return $this->capacity - $reserved;
    }
    //$event->is_fully_booked 
    public function getIsFullyBookedAttribute(): bool
    {
        return $this->remaining_capacity <= 0;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = \Str::slug($event->title);
            }
        });

      
        static::created(function ($event) {
            if ($event->wasRecentlyCreated) {
                Log::info('New event created', [
                    'event_id' => $event->id,
                    'title' => $event->title,
                    'organizer' => $event->organizer->name
                ]);
            }
        });
    }
}
