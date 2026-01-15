<?php

namespace App\Policies;

use App\Models\Pledge;
use App\Models\User;

class PledgePolicy
{
    public function view(User $user, Pledge $pledge): bool
    {
        return $user->id === $pledge->user_id || $user->isAdmin();
    }

    public function update(User $user, Pledge $pledge): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Pledge $pledge): bool
    {
        return $user->isAdmin();
    }
}
