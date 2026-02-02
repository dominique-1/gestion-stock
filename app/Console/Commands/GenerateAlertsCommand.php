<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Alert;
use Illuminate\Console\Command;

class GenerateAlertsCommand extends Command
{
    protected $signature = 'alerts:generate';
    protected $description = 'Generate alerts for low stock, overstock, and expiring products';

    public function handle()
    {
        $this->info('Generating alerts...');

        // Low stock alerts
        $lowStock = Product::lowStock()->get();
        foreach ($lowStock as $product) {
            Alert::firstOrCreate([
                'type' => 'min_stock',
                'product_id' => $product->id,
                'is_read' => false,
            ], [
                'message' => "Stock faible pour {$product->name} ({$product->current_stock} / min {$product->stock_min})",
                'level' => $product->current_stock === 0 ? 'critical' : 'warning',
            ]);
        }

        // Overstock alerts
        $overstock = Product::overstock()->get();
        foreach ($overstock as $product) {
            Alert::firstOrCreate([
                'type' => 'overstock',
                'product_id' => $product->id,
                'is_read' => false,
            ], [
                'message' => "Surstock pour {$product->name} ({$product->current_stock} / optimal {$product->stock_optimal})",
                'level' => 'info',
            ]);
        }

        // Expiring soon alerts
        $expiring = Product::expiringSoon(7)->get();
        foreach ($expiring as $product) {
            Alert::firstOrCreate([
                'type' => 'expiry_soon',
                'product_id' => $product->id,
                'is_read' => false,
            ], [
                'message' => "Produit proche de l'expiration : {$product->name} (expire le {$product->expires_at->format('d/m/Y')})",
                'level' => 'warning',
            ]);
        }

        $this->info('Alerts generated successfully.');
        return 0;
    }
}
