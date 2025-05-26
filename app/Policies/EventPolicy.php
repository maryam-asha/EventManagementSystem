<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view events');
    }

    public function view(User $user, Event $event): bool
    {
        return $user->hasPermissionTo('view events');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create events');
    }

    public function update(User $user, Event $event): bool
    {
        if (!$user->hasPermissionTo('edit events')) {
            return false;
        }

        // Organizers can only edit their own events
        if ($user->hasRole('organizer')) {
            return $user->id === $event->user_id;
        }

        return true;
    }

    public function delete(User $user, Event $event): bool
    {
        if (!$user->hasPermissionTo('delete events')) {
            return false;
        }

        // Organizers can only delete their own events
        if ($user->hasRole('organizer')) {
            return $user->id === $event->user_id;
        }

        return true;
    }

    public function publish(User $user, Event $event): bool
    {
        return $user->hasPermissionTo('publish events');
    }

    public function feature(User $user, Event $event): bool
    {
        return $user->hasPermissionTo('feature events');
    }
}
