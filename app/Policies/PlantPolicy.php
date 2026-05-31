<?php

namespace App\Policies;

use App\Models\Plant;
use App\Models\User;

class PlantPolicy
{
    /**
     * Determine if the user can view the plant
     */
    public function view(User $user, Plant $plant)
    {
        // Owner dapat lihat plants mereka
        if ($user->role === 'owner' && $plant->owner_id === $user->id) {
            return true;
        }

        // Worker yang assigned dapat lihat
        if ($user->role === 'worker') {
            return $plant->activities()->where('assigned_user_id', $user->id)->exists();
        }

        // Penyuluh dapat lihat semua
        if ($user->role === 'penyuluh') {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can create plants
     */
    public function create(User $user)
    {
        return $user->role === 'owner';
    }

    /**
     * Determine if the user can update the plant
     */
    public function update(User $user, Plant $plant)
    {
        return $user->role === 'owner' && $plant->owner_id === $user->id;
    }

    /**
     * Determine if the user can delete the plant
     */
    public function delete(User $user, Plant $plant)
    {
        return $user->role === 'owner' && $plant->owner_id === $user->id;
    }
}
