<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class AdminPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function view(User $admin, User $cur_admin): bool
    {
        //
        return $admin?->id === $cur_admin->id || $admin?->id === 1;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function edit(User $admin, User $cur_admin): bool
    {
        //
        return $admin?->id === $cur_admin->id || $admin?->id === 1;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $admin, User $cur_admin): bool
    {
        //
        return $admin?->id === $cur_admin->id || $admin?->id === 1;
    }
}
