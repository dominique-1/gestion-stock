<?php

namespace App\Policies;

use App\Models\Alert;
use App\Models\User;

class AlertPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canViewDashboard();
    }

    public function view(User $user, Alert $alert): bool
    {
        return $user->canViewDashboard();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function markAsRead(User $user, Alert $alert): bool
    {
        return $user->canViewDashboard();
    }

    public function delete(User $user, Alert $alert): bool
    {
        return $user->isAdmin();
    }
}
