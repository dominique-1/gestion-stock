<?php

namespace App\Policies;

use App\Models\StockMovement;
use App\Models\User;

class StockMovementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canViewDashboard();
    }

    public function view(User $user, StockMovement $movement): bool
    {
        return $user->canViewDashboard();
    }

    public function create(User $user): bool
    {
        return $user->canManageMovements();
    }

    public function update(User $user, StockMovement $movement): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, StockMovement $movement): bool
    {
        return $user->isAdmin();
    }
}
