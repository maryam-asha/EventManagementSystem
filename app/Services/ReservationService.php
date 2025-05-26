<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ReservationService
{
    /**
     * Get all reservations with optional filters
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function index(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Reservation::query()
            ->with(['event', 'user']);

        // Apply status filter
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Apply event filter
        if (isset($filters['event'])) {
            $query->where('event_id', $filters['event']);
        }

        // Apply user filter
        if (isset($filters['user'])) {
            $query->where('user_id', $filters['user']);
        }

        // Apply upcoming filter
        if (isset($filters['upcoming']) && $filters['upcoming']) {
            $query->whereHas('event', function ($q) {
                $q->where('start_date', '>', now());
            });
        }

        // Apply past filter
        if (isset($filters['past']) && $filters['past']) {
            $query->whereHas('event', function ($q) {
                $q->where('end_date', '<', now());
            });
        }

        // Apply sorting
        $sortField = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * Create a new reservation
     *
     * @param array $data
     * @return Reservation
     */
    public function store(array $data): Reservation
    {
        $event = Event::findOrFail($data['event_id']);
        
        return Reservation::create([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
            'quantity' => $data['quantity'],
            'total_price' => $event->price * $data['quantity'],
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
        ]);
    }

    /**
     * Get a specific reservation
     *
     * @param Reservation $reservation
     * @return Reservation
     */
    public function show(Reservation $reservation): Reservation
    {
        return $reservation->load(['event', 'user']);
    }

    /**
     * Update a reservation
     *
     * @param Reservation $reservation
     * @param array $data
     * @return Reservation
     */
    public function update(Reservation $reservation, array $data): Reservation
    {
        $reservation->update($data);
        return $reservation->fresh(['event', 'user']);
    }

    /**
     * Delete a reservation
     *
     * @param Reservation $reservation
     * @return bool
     */
    public function delete(Reservation $reservation): bool
    {
        return $reservation->delete();
    }

    /**
     * Cancel a reservation
     *
     * @param Reservation $reservation
     * @param string|null $reason
     * @return bool
     */
    public function cancel(Reservation $reservation, ?string $reason = null): bool
    {
        return $reservation->cancel($reason);
    }

    /**
     * Confirm a reservation
     *
     * @param Reservation $reservation
     * @return bool
     */
    public function confirm(Reservation $reservation): bool
    {
        return $reservation->confirm();
    }

    /**
     * Complete a reservation
     *
     * @param Reservation $reservation
     * @return bool
     */
    public function complete(Reservation $reservation): bool
    {
        return $reservation->complete();
    }

    /**
     * Get reservations by status
     *
     * @param string $status
     * @return Collection
     */
    public function getByStatus(string $status): Collection
    {
        return Reservation::with(['event', 'user'])
            ->where('status', $status)
            ->latest()
            ->get();
    }

    /**
     * Get user's reservations
     *
     * @param int $userId
     * @return Collection
     */
    public function getUserReservations(int $userId): Collection
    {
        return Reservation::with(['event', 'user'])
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }
}
