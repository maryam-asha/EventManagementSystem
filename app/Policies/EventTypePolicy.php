<?php

namespace App\Policies;

use App\Models\EventType;
use App\Models\User;

class EventTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view event types');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EventType $eventType): bool
    {
        return $user->hasPermissionTo('view event types');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create event types');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EventType $eventType): bool
    {
        return $user->hasPermissionTo('edit event types');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EventType $eventType): bool
    {
        return $user->hasPermissionTo('delete event types');
    }
} 