<?php

namespace Tests\Unit;

use App\Models\StockMovement;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockMovementApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer un produit de test
        $this->product = Product::factory()->create([
            'name' => 'Laptop Pro 15"',
            'current_stock' => 20,
            'stock_min' => 5,
            'stock_optimal' => 30
        ]);
        
        // Créer un utilisateur de test
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);
    }

    public function test_can_create_stock_in_movement()
    {
        $movementData = [
            'product_id' => $this->product->id,
            'type' => 'in',
            'reason' => 'Nouvelle livraison',
            'quantity' => 10,
            'moved_at' => now(),
            'note' => 'Livraison du fournisseur TechSupplier',
            'user_id' => $this->user->id
        ];

        $movement = StockMovement::create($movementData);

        $this->assertInstanceOf(StockMovement::class, $movement);
        $this->assertEquals('in', $movement->type);
        $this->assertEquals(10, $movement->quantity);
        $this->assertEquals($this->product->id, $movement->product_id);
    }

    public function test_can_create_stock_out_movement()
    {
        $movementData = [
            'product_id' => $this->product->id,
            'type' => 'out',
            'reason' => 'Vente client',
            'quantity' => 5,
            'moved_at' => now(),
            'note' => 'Vente au client ABC',
            'user_id' => $this->user->id
        ];

        $movement = StockMovement::create($movementData);

        $this->assertInstanceOf(StockMovement::class, $movement);
        $this->assertEquals('out', $movement->type);
        $this->assertEquals(5, $movement->quantity);
    }

    public function test_stock_movement_updates_product_stock()
    {
        // Mouvement d'entrée
        $inMovement = StockMovement::create([
            'product_id' => $this->product->id,
            'type' => 'in',
            'reason' => 'Livraison',
            'quantity' => 15,
            'moved_at' => now(),
            'user_id' => $this->user->id
        ]);

        // Mettre à jour le stock du produit
        $this->product->current_stock += $inMovement->quantity;
        $this->product->save();

        $this->assertEquals(35, $this->product->fresh()->current_stock);

        // Mouvement de sortie
        $outMovement = StockMovement::create([
            'product_id' => $this->product->id,
            'type' => 'out',
            'reason' => 'Vente',
            'quantity' => 8,
            'moved_at' => now(),
            'user_id' => $this->user->id
        ]);

        // Mettre à jour le stock du produit
        $this->product->current_stock -= $outMovement->quantity;
        $this->product->save();

        $this->assertEquals(27, $this->product->fresh()->current_stock);
    }

    public function test_cannot_create_out_movement_with_insufficient_stock()
    {
        // Produit avec stock faible
        $lowStockProduct = Product::factory()->create([
            'current_stock' => 3
        ]);

        // Tentative de mouvement de sortie supérieur au stock
        $this->expectException(\Exception::class);
        
        StockMovement::create([
            'product_id' => $lowStockProduct->id,
            'type' => 'out',
            'reason' => 'Vente',
            'quantity' => 5, // Supérieur au stock disponible
            'moved_at' => now(),
            'user_id' => $this->user->id
        ]);
    }

    public function test_stock_movement_filter_by_type()
    {
        // Créer des mouvements de différents types
        StockMovement::factory()->count(3)->create([
            'product_id' => $this->product->id,
            'type' => 'in',
            'user_id' => $this->user->id
        ]);

        StockMovement::factory()->count(2)->create([
            'product_id' => $this->product->id,
            'type' => 'out',
            'user_id' => $this->user->id
        ]);

        $inMovements = StockMovement::where('type', 'in')->get();
        $outMovements = StockMovement::where('type', 'out')->get();

        $this->assertCount(3, $inMovements);
        $this->assertCount(2, $outMovements);
    }

    public function test_stock_movement_filter_by_date_range()
    {
        $startDate = now()->subDays(10);
        $endDate = now()->subDays(5);

        // Créer des mouvements dans différentes périodes
        StockMovement::factory()->create([
            'product_id' => $this->product->id,
            'type' => 'in',
            'moved_at' => $startDate->copy()->addDays(2),
            'user_id' => $this->user->id
        ]);

        StockMovement::factory()->create([
            'product_id' => $this->product->id,
            'type' => 'out',
            'moved_at' => $startDate->copy()->addDays(7),
            'user_id' => $this->user->id
        ]);

        StockMovement::factory()->create([
            'product_id' => $this->product->id,
            'type' => 'in',
            'moved_at' => now()->subDays(2), // Hors de la plage
            'user_id' => $this->user->id
        ]);

        $filteredMovements = StockMovement::whereDate('moved_at', '>=', $startDate)
            ->whereDate('moved_at', '<=', $endDate)
            ->get();

        $this->assertCount(1, $filteredMovements);
    }

    public function test_stock_movement_search_by_reason()
    {
        StockMovement::factory()->create([
            'product_id' => $this->product->id,
            'type' => 'in',
            'reason' => 'Livraison fournisseur principal',
            'user_id' => $this->user->id
        ]);

        StockMovement::factory()->create([
            'product_id' => $this->product->id,
            'type' => 'out',
            'reason' => 'Vente client standard',
            'user_id' => $this->user->id
        ]);

        $searchResults = StockMovement::where('reason', 'like', '%fournisseur%')->get();

        $this->assertCount(1, $searchResults);
        $this->assertStringContainsString('fournisseur', $searchResults->first()->reason);
    }

    public function test_stock_movement_product_relationship()
    {
        $movement = StockMovement::factory()->create([
            'product_id' => $this->product->id,
            'type' => 'in',
            'user_id' => $this->user->id
        ]);

        $this->assertInstanceOf(Product::class, $movement->product);
        $this->assertEquals($this->product->name, $movement->product->name);
    }

    public function test_stock_movement_user_relationship()
    {
        $movement = StockMovement::factory()->create([
            'product_id' => $this->product->id,
            'type' => 'in',
            'user_id' => $this->user->id
        ]);

        $this->assertInstanceOf(User::class, $movement->user);
        $this->assertEquals($this->user->name, $movement->user->name);
    }

    public function test_stock_movement_validation_rules()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        // Tentative de créer un mouvement sans produit
        StockMovement::create([
            'type' => 'in',
            'reason' => 'Test',
            'quantity' => 10,
            'moved_at' => now(),
            'user_id' => $this->user->id
        ]);
    }

    public function test_stock_movement_quantity_must_be_positive()
    {
        $this->expectException(\Exception::class);
        
        StockMovement::create([
            'product_id' => $this->product->id,
            'type' => 'in',
            'reason' => 'Test',
            'quantity' => -5, // Quantité négative
            'moved_at' => now(),
            'user_id' => $this->user->id
        ]);
    }

    public function test_stock_movement_ordering()
    {
        // Créer des mouvements à différents moments
        $movement1 = StockMovement::factory()->create([
            'product_id' => $this->product->id,
            'type' => 'in',
            'moved_at' => now()->subHours(2),
            'user_id' => $this->user->id
        ]);

        $movement2 = StockMovement::factory()->create([
            'product_id' => $this->product->id,
            'type' => 'out',
            'moved_at' => now()->subHours(1),
            'user_id' => $this->user->id
        ]);

        $movement3 = StockMovement::factory()->create([
            'product_id' => $this->product->id,
            'type' => 'in',
            'moved_at' => now(),
            'user_id' => $this->user->id
        ]);

        $orderedMovements = StockMovement::orderBy('moved_at', 'desc')->get();

        $this->assertEquals($movement3->id, $orderedMovements->first()->id);
        $this->assertEquals($movement1->id, $orderedMovements->last()->id);
    }
}
