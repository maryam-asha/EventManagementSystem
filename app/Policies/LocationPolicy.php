<?php

namespace App\Policies;

use App\Models\Location;
use App\Models\User;

class LocationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view locations');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Location $location): bool
    {
        return $user->hasPermissionTo('view locations');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create locations');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Location $location): bool
    {
        return $user->hasPermissionTo('edit locations');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Location $location): bool
    {
        return $user->hasPermissionTo('delete locations');
    }
} 