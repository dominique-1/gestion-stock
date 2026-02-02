<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => \App\Policies\UserPolicy::class,
        Category::class => \App\Policies\CategoryPolicy::class,
        Product::class => \App\Policies\ProductPolicy::class,
        StockMovement::class => \App\Policies\StockMovementPolicy::class,
        Inventory::class => \App\Policies\InventoryPolicy::class,
        Alert::class => \App\Policies\AlertPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
