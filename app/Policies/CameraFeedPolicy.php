<?php

namespace App\Policies;

use App\Models\CameraFeed;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CameraFeedPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CameraFeed $cameraFeed): bool
    {
        // Admin and Staff can view all feeds for operations
        if ($user->role === 'admin' || $user->role === 'staff') {
            return true;
        }

        // Parents can only view feeds for rooms where their children are assigned
        if ($user->role === 'parent') {
            return $user->students()
                ->where('room_id', $cameraFeed->room_id)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CameraFeed $cameraFeed): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CameraFeed $cameraFeed): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CameraFeed $cameraFeed): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CameraFeed $cameraFeed): bool
    {
        return false;
    }
}
