<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function createdAlerts()
    {
        return $this->hasMany(Alert::class, 'created_by');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isViewer(): bool
    {
        return $this->role === 'viewer';
    }

    public function canManageUsers(): bool
    {
        return $this->isAdmin();
    }

    public function canManageProducts(): bool
    {
        return in_array($this->role, ['admin', 'manager']);
    }

    public function canManageMovements(): bool
    {
        return in_array($this->role, ['admin', 'manager']);
    }

    public function canManageInventories(): bool
    {
        return in_array($this->role, ['admin', 'manager']);
    }

    public function canViewDashboard(): bool
    {
        return in_array($this->role, ['admin', 'manager', 'viewer']);
    }
}
