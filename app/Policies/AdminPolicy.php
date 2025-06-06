<?php

namespace App\Policies;

use App\Models\User;

class AdminPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function accessAdmin(User $user)
    {
        return $user->hasRole('Admin'); // Hanya Admin yang bisa akses
    }
}
