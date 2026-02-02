<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Carbon\Carbon;

class MovementSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@stock.test')->first();
        $laptop = Product::where('barcode', 'LP001')->first();
        $monitor = Product::where('barcode', 'MN001')->first();
        $stylos = Product::where('barcode', 'ST001')->first();

        $movements = [
            [
                'product_id' => $laptop->id,
                'type' => 'in',
                'reason' => 'achat',
                'quantity' => 10,
                'moved_at' => Carbon::now()->subDays(30),
                'user_id' => $admin->id,
                'note' => 'Commande initiale',
            ],
            [
                'product_id' => $laptop->id,
                'type' => 'out',
                'reason' => 'vente',
                'quantity' => 3,
                'moved_at' => Carbon::now()->subDays(20),
                'user_id' => $admin->id,
                'note' => 'Vente client A',
            ],
            [
                'product_id' => $monitor->id,
                'type' => 'in',
                'reason' => 'achat',
                'quantity' => 15,
                'moved_at' => Carbon::now()->subDays(25),
                'user_id' => $admin->id,
                'note' => 'Réception stock',
            ],
            [
                'product_id' => $stylos->id,
                'type' => 'out',
                'reason' => 'casse',
                'quantity' => 5,
                'moved_at' => Carbon::now()->subDays(10),
                'user_id' => $admin->id,
                'note' => 'Perte inventaire',
            ],
        ];

        foreach ($movements as $m) {
            StockMovement::create($m);
        }
    }
}
