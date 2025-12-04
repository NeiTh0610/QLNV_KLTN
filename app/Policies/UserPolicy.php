<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function manageEmployees(User $user): bool
    {
        return $user->canManageEmployees();
    }
}

