<?php

namespace App\Policies;

use App\Models\Inventory;
use App\Models\User;

class InventoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canViewDashboard();
    }

    public function view(User $user, Inventory $inventory): bool
    {
        return $user->canViewDashboard();
    }

    public function create(User $user): bool
    {
        return $user->canManageInventories();
    }

    public function update(User $user, Inventory $inventory): bool
    {
        return $user->canManageInventories() && $inventory->status === 'draft';
    }

    public function close(User $user, Inventory $inventory): bool
    {
        return $user->canManageInventories() && $inventory->status === 'draft';
    }

    public function delete(User $user, Inventory $inventory): bool
    {
        return $user->isAdmin() && $inventory->status === 'draft';
    }
}
