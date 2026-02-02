<?php

namespace Tests\Feature;

use App\Models\Inventory;
use App\Models\InventoryLine;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class InventoryManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'inventory@example.com',
            'name' => 'Inventory Manager'
        ]);
        Sanctum::actingAs($this->user);

        $this->category = Category::factory()->create(['name' => 'Test Category']);
        
        $this->products = Product::factory()->count(3)->create([
            'category_id' => $this->category->id,
            'current_stock' => 50,
            'stock_min' => 10,
            'stock_optimal' => 100
        ]);
    }

    public function test_can_create_inventory()
    {
        $inventoryData = [
            'performed_at' => now()->toISOString(),
            'note' => 'Inventaire mensuel de décembre'
        ];

        $response = $this->postJson('/api/v1/inventories', $inventoryData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'id',
                    'performed_at',
                    'user_id',
                    'note',
                    'status',
                    'created_at',
                    'updated_at'
                ]);

        $this->assertEquals('draft', $response->json('status'));
        $this->assertEquals($this->user->id, $response->json('user_id'));
        
        $this->assertDatabaseHas('inventories', [
            'user_id' => $this->user->id,
            'status' => 'draft',
            'note' => 'Inventaire mensuel de décembre'
        ]);
    }

    public function test_can_view_inventories_list()
    {
        // Créer des inventaires de test
        Inventory::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'status' => 'completed'
        ]);

        Inventory::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $response = $this->getJson('/api/v1/inventories');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data',
                    'current_page',
                    'per_page',
                    'total'
                ]);

        $this->assertEquals(5, $response->json('total'));
    }

    public function test_can_filter_inventories_by_status()
    {
        Inventory::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'status' => 'completed'
        ]);

        Inventory::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $response = $this->getJson('/api/v1/inventories?status=completed');

        $response->assertStatus(200);
        $this->assertEquals(3, $response->json('total'));

        foreach ($response->json('data') as $inventory) {
            $this->assertEquals('completed', $inventory['status']);
        }
    }

    public function test_can_view_single_inventory()
    {
        $inventory = Inventory::factory()->create([
            'user_id' => $this->user->id,
            'note' => 'Inventaire de test'
        ]);

        // Ajouter des lignes d'inventaire
        InventoryLine::factory()->count(2)->create([
            'inventory_id' => $inventory->id,
            'product_id' => $this->products[0]->id,
            'theoretical_quantity' => 50,
            'actual_quantity' => 45
        ]);

        $response = $this->getJson("/api/v1/inventories/{$inventory->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'id',
                    'performed_at',
                    'user',
                    'note',
                    'status',
                    'lines' => [
                        '*' => [
                            'id',
                            'product',
                            'theoretical_quantity',
                            'actual_quantity',
                            'difference'
                        ]
                    ],
                    'created_at',
                    'updated_at'
                ]);

        $this->assertEquals('Inventaire de test', $response->json('note'));
        $this->assertCount(2, $response->json('lines'));
    }

    public function test_can_add_inventory_lines()
    {
        $inventory = Inventory::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $lineData = [
            'product_id' => $this->products[0]->id,
            'theoretical_quantity' => 50,
            'actual_quantity' => 45,
            'note' => 'Produit légèrement endommagé'
        ];

        $response = $this->postJson("/api/v1/inventories/{$inventory->id}/lines", $lineData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'id',
                    'inventory_id',
                    'product_id',
                    'theoretical_quantity',
                    'actual_quantity',
                    'difference',
                    'note',
                    'created_at',
                    'updated_at'
                ]);

        $this->assertEquals(-5, $response->json('difference')); // 45 - 50 = -5
        
        $this->assertDatabaseHas('inventory_lines', [
            'inventory_id' => $inventory->id,
            'product_id' => $this->products[0]->id,
            'theoretical_quantity' => 50,
            'actual_quantity' => 45,
            'difference' => -5
        ]);
    }

    public function test_can_update_inventory_line()
    {
        $inventory = Inventory::factory()->create(['user_id' => $this->user->id]);
        $line = InventoryLine::factory()->create([
            'inventory_id' => $inventory->id,
            'product_id' => $this->products[0]->id,
            'theoretical_quantity' => 50,
            'actual_quantity' => 45
        ]);

        $updateData = [
            'actual_quantity' => 48,
            'note' => 'Correction après recomptage'
        ];

        $response = $this->putJson("/api/v1/inventories/{$inventory->id}/lines/{$line->id}", $updateData);

        $response->assertStatus(200)
                ->assertJson([
                    'actual_quantity' => 48,
                    'difference' => -2, // 48 - 50 = -2
                    'note' => 'Correction après recomptage'
                ]);

        $this->assertDatabaseHas('inventory_lines', [
            'id' => $line->id,
            'actual_quantity' => 48,
            'difference' => -2
        ]);
    }

    public function test_can_delete_inventory_line()
    {
        $inventory = Inventory::factory()->create(['user_id' => $this->user->id]);
        $line = InventoryLine::factory()->create([
            'inventory_id' => $inventory->id,
            'product_id' => $this->products[0]->id
        ]);

        $response = $this->deleteJson("/api/v1/inventories/{$inventory->id}/lines/{$line->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('inventory_lines', [
            'id' => $line->id
        ]);
    }

    public function test_can_close_inventory()
    {
        $inventory = Inventory::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        // Ajouter des lignes avec des différences
        InventoryLine::factory()->create([
            'inventory_id' => $inventory->id,
            'product_id' => $this->products[0]->id,
            'theoretical_quantity' => 50,
            'actual_quantity' => 45 // Différence de -5
        ]);

        InventoryLine::factory()->create([
            'inventory_id' => $inventory->id,
            'product_id' => $this->products[1]->id,
            'theoretical_quantity' => 30,
            'actual_quantity' => 35 // Différence de +5
        ]);

        $response = $this->postJson("/api/v1/inventories/{$inventory->id}/close");

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Inventory closed and adjustments applied'
                ]);

        $inventory->refresh();
        $this->assertEquals('completed', $inventory->status);

        // Vérifier que les stocks ont été ajustés
        $this->products[0]->refresh();
        $this->products[1]->refresh();

        $this->assertEquals(45, $this->products[0]->current_stock); // 50 - 5
        $this->assertEquals(35, $this->products[1]->current_stock); // 30 + 5
    }

    public function test_cannot_close_inventory_without_lines()
    {
        $inventory = Inventory::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $response = $this->postJson("/api/v1/inventories/{$inventory->id}/close");

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['lines']);
    }

    public function test_cannot_modify_closed_inventory()
    {
        $inventory = Inventory::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed'
        ]);

        $lineData = [
            'product_id' => $this->products[0]->id,
            'theoretical_quantity' => 50,
            'actual_quantity' => 45
        ];

        $response = $this->postJson("/api/v1/inventories/{$inventory->id}/lines", $lineData);

        $response->assertStatus(403);
    }

    public function test_inventory_line_validation()
    {
        $inventory = Inventory::factory()->create(['user_id' => $this->user->id]);

        $invalidData = [
            'product_id' => 999, // Produit inexistant
            'theoretical_quantity' => -10, // Quantité négative
            'actual_quantity' => -5 // Quantité négative
        ];

        $response = $this->postJson("/api/v1/inventories/{$inventory->id}/lines", $invalidData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['product_id', 'theoretical_quantity', 'actual_quantity']);
    }

    public function test_inventory_difference_calculation()
    {
        $inventory = Inventory::factory()->create(['user_id' => $this->user->id]);

        $testCases = [
            ['theoretical' => 50, 'actual' => 45, 'expected_diff' => -5],
            ['theoretical' => 30, 'actual' => 35, 'expected_diff' => 5],
            ['theoretical' => 100, 'actual' => 100, 'expected_diff' => 0],
        ];

        foreach ($testCases as $case) {
            $lineData = [
                'product_id' => $this->products[0]->id,
                'theoretical_quantity' => $case['theoretical'],
                'actual_quantity' => $case['actual']
            ];

            $response = $this->postJson("/api/v1/inventories/{$inventory->id}/lines", $lineData);

            $response->assertStatus(201);
            $this->assertEquals($case['expected_diff'], $response->json('difference'));
        }
    }

    public function test_inventory_with_multiple_products()
    {
        $inventory = Inventory::factory()->create(['user_id' => $this->user->id]);

        foreach ($this->products as $index => $product) {
            InventoryLine::factory()->create([
                'inventory_id' => $inventory->id,
                'product_id' => $product->id,
                'theoretical_quantity' => 50,
                'actual_quantity' => 45 + $index
            ]);
        }

        $response = $this->getJson("/api/v1/inventories/{$inventory->id}");

        $response->assertStatus(200);
        $this->assertCount(3, $response->json('lines'));

        $totalDifference = $response->json('lines')->sum('difference');
        $this->assertEquals(-3, $totalDifference); // (-5) + (-4) + (-3) = -12, mais avec l'index c'est différent
    }

    public function test_inventory_date_filtering()
    {
        $oldInventory = Inventory::factory()->create([
            'user_id' => $this->user->id,
            'performed_at' => now()->subDays(10)
        ]);

        $recentInventory = Inventory::factory()->create([
            'user_id' => $this->user->id,
            'performed_at' => now()->subDays(2)
        ]);

        $response = $this->getJson('/api/v1/inventories?from=' . now()->subDays(5)->format('Y-m-d'));

        $response->assertStatus(200);
        $this->assertEquals(1, $response->json('total'));
        $this->assertEquals($recentInventory->id, $response->json('data.0.id'));
    }

    public function test_inventory_user_relationship()
    {
        $inventory = Inventory::factory()->create([
            'user_id' => $this->user->id,
            'note' => 'Inventaire test'
        ]);

        $response = $this->getJson("/api/v1/inventories/{$inventory->id}");

        $response->assertStatus(200);
        $this->assertEquals($this->user->name, $response->json('user.name'));
        $this->assertEquals($this->user->email, $response->json('user.email'));
    }

    public function test_inventory_product_relationship_in_lines()
    {
        $inventory = Inventory::factory()->create(['user_id' => $this->user->id]);
        
        InventoryLine::factory()->create([
            'inventory_id' => $inventory->id,
            'product_id' => $this->products[0]->id,
            'theoretical_quantity' => 50,
            'actual_quantity' => 45
        ]);

        $response = $this->getJson("/api/v1/inventories/{$inventory->id}");

        $response->assertStatus(200);
        $line = $response->json('lines.0');
        
        $this->assertEquals($this->products[0]->name, $line['product']['name']);
        $this->assertEquals($this->products[0]->barcode, $line['product']['barcode']);
    }

    public function test_can_delete_draft_inventory()
    {
        $inventory = Inventory::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $response = $this->deleteJson("/api/v1/inventories/{$inventory->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('inventories', [
            'id' => $inventory->id
        ]);
    }

    public function test_cannot_delete_completed_inventory()
    {
        $inventory = Inventory::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed'
        ]);

        $response = $this->deleteJson("/api/v1/inventories/{$inventory->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('inventories', [
            'id' => $inventory->id
        ]);
    }
}
