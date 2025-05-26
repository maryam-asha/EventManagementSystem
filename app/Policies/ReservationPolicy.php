<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReservationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view reservations');
    }

    public function view(User $user, Reservation $reservation): bool
    {
        if (!$user->hasPermissionTo('view reservations')) {
            return false;
        }

        // Users can only view their own reservations
        if ($user->hasRole('user')) {
            return $user->id === $reservation->user_id;
        }

        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create reservations');
    }

    public function update(User $user, Reservation $reservation): bool
    {
        if (!$user->hasPermissionTo('edit reservations')) {
            return false;
        }

        // Users can only edit their own reservations
        if ($user->hasRole('user')) {
            return $user->id === $reservation->user_id;
        }

        return true;
    }

    public function delete(User $user, Reservation $reservation): bool
    {
        if (!$user->hasPermissionTo('delete reservations')) {
            return false;
        }

        // Users can only delete their own reservations
        if ($user->hasRole('user')) {
            return $user->id === $reservation->user_id;
        }

        return true;
    }

    public function cancel(User $user, Reservation $reservation): bool
    {
        if (!$user->hasPermissionTo('cancel reservations')) {
            return false;
        }

        // Users can only cancel their own reservations
        if ($user->hasRole('user')) {
            return $user->id === $reservation->user_id;
        }

        return true;
    }

    public function confirm(User $user, Reservation $reservation): bool
    {
        return $user->hasPermissionTo('confirm reservations');
    }
} 