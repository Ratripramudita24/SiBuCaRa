<?php

namespace App\Policies;

use App\Models\PlantActivity;
use App\Models\User;

class PlantActivityPolicy
{
    /**
     * Determine if the user can view the activity
     */
    public function view(User $user, PlantActivity $activity)
    {
        // Owner dapat lihat activities milik tanaman mereka
        if ($user->role === 'owner' && $activity->plant->owner_id === $user->id) {
            return true;
        }

        // Assigned worker dapat lihat
        if ($user->role === 'worker' && $activity->assigned_user_id === $user->id) {
            return true;
        }

        // Penyuluh dapat lihat semua
        if ($user->role === 'penyuluh') {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can update activity status
     */
    public function updateStatus(User $user, PlantActivity $activity)
    {
        // Assigned worker atau owner dapat update status
        if ($user->role === 'worker' && $activity->assigned_user_id === $user->id) {
            return true;
        }

        if ($user->role === 'owner' && $activity->plant->owner_id === $user->id) {
            return true;
        }

        return false;
    }
}
