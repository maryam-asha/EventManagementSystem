<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Reservation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'event_id',
        'user_id',
        'quantity',
        'total_price',
        'status',
        'notes',
        'cancelled_at',
        'cancellation_reason',
    ];



    
    protected $casts = [
        'quantity' => 'integer',
        'total_price' => 'decimal:2',
        'cancelled_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    //Scopes 
    //Resevation::pending->get()
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    //Resevation::confirmed->get()
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    //Resevation::cancelled->get()
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    //Resevation::completed->get()
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    
    public function cancel(string $reason = null): bool
    {
        return $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
        ]);
    }

    public function confirm(): bool
    {
        return $this->update(['status' => 'confirmed']);
    }

    public function complete(): bool
    {
        return $this->update(['status' => 'completed']);
    }

    
    //Accessors 
    //$reservation->is_cancelled 
    public function getIsCancelledAttribute(): bool
    {
        return $this->status === 'cancelled';
    }

    //$reservation->is_confirmed 
    public function getIsConfirmedAttribute(): bool
    {
        return $this->status === 'confirmed';
    }

    //$reservation->is_completed 
    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'completed';
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($reservation) {
            // Check if status is being changed
            if ($reservation->isDirty('status')) {
                $oldStatus = $reservation->getOriginal('status');
                $newStatus = $reservation->status;

                Log::info('Reservation status changed', [
                    'reservation_id' => $reservation->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'user' => $reservation->user->name,
                    'event' => $reservation->event->title
                ]);

                
            }

            // Check if quantity is being changed
            if ($reservation->isDirty('quantity')) {
                $oldQuantity = $reservation->getOriginal('quantity');
                $newQuantity = $reservation->quantity;

                // Recalculate total price
                $reservation->total_price = $reservation->event->price * $newQuantity;

                Log::info('Reservation quantity changed', [
                    'reservation_id' => $reservation->id,
                    'old_quantity' => $oldQuantity,
                    'new_quantity' => $newQuantity,
                    'new_total_price' => $reservation->total_price
                ]);
            }
        });
    }
}
