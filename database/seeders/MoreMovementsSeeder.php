<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\StockMovement;
use Carbon\Carbon;

class MoreMovementsSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        
        // Créer 100 mouvements aléatoires sur les 30 derniers jours
        for ($i = 0; $i < 100; $i++) {
            $product = $products->random();
            $type = rand(0, 1) ? 'in' : 'out';
            $quantity = rand(1, 20);
            $date = Carbon::now()->subDays(rand(0, 30))->setTime(rand(8, 18), rand(0, 59));
            
            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => 1, // Admin
                'type' => $type,
                'quantity' => $quantity,
                'reason' => ['achat', 'vente', 'retour', 'casse', 'autre'][rand(0, 4)],
                'moved_at' => $date,
                'note' => 'Mouvement de test',
            ]);
        }
    }
}
