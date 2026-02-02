<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un utilisateur authentifié
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'name' => 'Test User'
        ]);
        Sanctum::actingAs($this->user);

        // Créer des catégories de test
        $this->category = Category::factory()->create([
            'name' => 'Informatique',
            'description' => 'Produits informatiques'
        ]);
    }

    public function test_can_view_products_list()
    {
        // Créer des produits de test
        Product::factory()->count(5)->create(['category_id' => $this->category->id]);

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data',
                    'current_page',
                    'per_page',
                    'total'
                ]);

        $this->assertEquals(5, $response->json('total'));
    }

    public function test_can_create_product_with_valid_data()
    {
        $productData = [
            'name' => 'Laptop Pro 15"',
            'description' => 'Ordinateur portable haute performance',
            'barcode' => '1234567890123',
            'supplier' => 'TechSupplier',
            'price' => 1299.99,
            'category_id' => $this->category->id,
            'stock_min' => 5,
            'stock_optimal' => 20,
            'current_stock' => 15,
            'expires_at' => now()->addMonths(12)->toISOString()
        ];

        $response = $this->postJson('/api/v1/products', $productData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'id',
                    'name',
                    'description',
                    'barcode',
                    'supplier',
                    'price',
                    'category_id',
                    'stock_min',
                    'stock_optimal',
                    'current_stock',
                    'created_at',
                    'updated_at'
                ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Laptop Pro 15"',
            'barcode' => '1234567890123',
            'price' => 1299.99
        ]);
    }

    public function test_cannot_create_product_with_invalid_data()
    {
        $invalidData = [
            'name' => '', // Nom requis
            'description' => 'Description test',
            'price' => -10, // Prix négatif
            'stock_min' => -5, // Stock minimum négatif
            'stock_optimal' => 0, // Stock optimal doit être > 0
            'current_stock' => -1 // Stock actuel négatif
        ];

        $response = $this->postJson('/api/v1/products', $invalidData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'price', 'stock_min', 'stock_optimal', 'current_stock']);
    }

    public function test_can_view_single_product()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'Moniteur 27"'
        ]);

        $response = $this->getJson("/api/v1/products/{$product->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'id',
                    'name',
                    'description',
                    'barcode',
                    'supplier',
                    'price',
                    'category',
                    'stock_min',
                    'stock_optimal',
                    'current_stock',
                    'expires_at',
                    'created_at',
                    'updated_at'
                ]);

        $this->assertEquals('Moniteur 27"', $response->json('name'));
        $this->assertEquals($this->category->name, $response->json('category.name'));
    }

    public function test_can_update_product()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'Ancien nom',
            'price' => 999.99
        ]);

        $updateData = [
            'name' => 'Nouveau nom mis à jour',
            'description' => 'Description mise à jour',
            'price' => 1499.99,
            'stock_min' => 10,
            'stock_optimal' => 25,
            'current_stock' => 20
        ];

        $response = $this->putJson("/api/v1/products/{$product->id}", $updateData);

        $response->assertStatus(200)
                ->assertJson([
                    'name' => 'Nouveau nom mis à jour',
                    'price' => 1499.99
                ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Nouveau nom mis à jour',
            'price' => 1499.99
        ]);
    }

    public function test_can_delete_product()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'Produit à supprimer'
        ]);

        $response = $this->deleteJson("/api/v1/products/{$product->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id
        ]);
    }

    public function test_can_search_products_by_name()
    {
        Product::factory()->create([
            'name' => 'Laptop Gaming Pro',
            'category_id' => $this->category->id
        ]);

        Product::factory()->create([
            'name' => 'Laptop Office Basic',
            'category_id' => $this->category->id
        ]);

        Product::factory()->create([
            'name' => 'Moniteur 27"',
            'category_id' => $this->category->id
        ]);

        $response = $this->getJson('/api/v1/products?q=gaming');

        $response->assertStatus(200);
        $this->assertEquals(1, $response->json('total'));
        $this->assertStringContainsString('Gaming', $response->json('data.0.name'));
    }

    public function test_can_filter_products_by_category()
    {
        $category2 = Category::factory()->create(['name' => 'Bureau']);

        Product::factory()->count(3)->create(['category_id' => $this->category->id]);
        Product::factory()->count(2)->create(['category_id' => $category2->id]);

        $response = $this->getJson("/api/v1/products?category_id={$this->category->id}");

        $response->assertStatus(200);
        $this->assertEquals(3, $response->json('total'));

        foreach ($response->json('data') as $product) {
            $this->assertEquals($this->category->id, $product['category_id']);
        }
    }

    public function test_can_filter_low_stock_products()
    {
        // Produits avec stock faible
        Product::factory()->count(3)->create([
            'current_stock' => 2,
            'stock_min' => 5,
            'category_id' => $this->category->id
        ]);

        // Produits avec stock normal
        Product::factory()->count(2)->create([
            'current_stock' => 15,
            'stock_min' => 5,
            'category_id' => $this->category->id
        ]);

        $response = $this->getJson('/api/v1/products?low_stock=true');

        $response->assertStatus(200);
        $this->assertEquals(3, $response->json('total'));

        foreach ($response->json('data') as $product) {
            $this->assertLessThan($product['stock_min'], $product['current_stock']);
        }
    }

    public function test_can_filter_overstock_products()
    {
        // Produits en surstock
        Product::factory()->count(2)->create([
            'current_stock' => 25,
            'stock_optimal' => 20,
            'category_id' => $this->category->id
        ]);

        // Produits avec stock normal
        Product::factory()->count(3)->create([
            'current_stock' => 15,
            'stock_optimal' => 20,
            'category_id' => $this->category->id
        ]);

        $response = $this->getJson('/api/v1/products?overstock=true');

        $response->assertStatus(200);
        $this->assertEquals(2, $response->json('total'));

        foreach ($response->json('data') as $product) {
            $this->assertGreaterThan($product['stock_optimal'], $product['current_stock']);
        }
    }

    public function test_can_filter_expiring_soon_products()
    {
        // Produits qui expirent bientôt
        Product::factory()->count(2)->create([
            'expires_at' => now()->addDays(5),
            'category_id' => $this->category->id
        ]);

        // Produits avec expiration lointaine
        Product::factory()->count(3)->create([
            'expires_at' => now()->addMonths(6),
            'category_id' => $this->category->id
        ]);

        $response = $this->getJson('/api/v1/products?expiring_soon=true');

        $response->assertStatus(200);
        $this->assertEquals(2, $response->json('total'));
    }

    public function test_can_get_products_for_select()
    {
        Product::factory()->create([
            'name' => 'Laptop Pro',
            'barcode' => '123456789',
            'category_id' => $this->category->id
        ]);

        Product::factory()->create([
            'name' => 'Moniteur',
            'barcode' => '987654321',
            'category_id' => $this->category->id
        ]);

        $response = $this->getJson('/api/v1/select/products');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    '*' => [
                        'id',
                        'label'
                    ]
                ]);

        $this->assertEquals(2, count($response->json()));
        
        // Vérifier le format des labels
        $firstProduct = $response->json()[0];
        $this->assertArrayHasKey('id', $firstProduct);
        $this->assertArrayHasKey('label', $firstProduct);
        $this->assertStringContainsString('(', $firstProduct['label']); // Barcode inclus
    }

    public function test_product_validation_handles_duplicate_barcode()
    {
        Product::factory()->create([
            'barcode' => '1234567890123',
            'category_id' => $this->category->id
        ]);

        $productData = [
            'name' => 'Autre produit',
            'barcode' => '1234567890123', // Barcode dupliqué
            'category_id' => $this->category->id,
            'stock_min' => 5,
            'stock_optimal' => 20,
            'current_stock' => 15
        ];

        $response = $this->postJson('/api/v1/products', $productData);

        // Selon la configuration de validation, cela peut échouer ou réussir
        // Le test vérifie juste que le système gère la situation
        $response->assertStatus(422);
    }

    public function test_product_price_formatting()
    {
        $productData = [
            'name' => 'Produit test',
            'price' => 1299.999, // Plus de 2 décimales
            'category_id' => $this->category->id,
            'stock_min' => 5,
            'stock_optimal' => 20,
            'current_stock' => 15
        ];

        $response = $this->postJson('/api/v1/products', $productData);

        $response->assertStatus(201);
        
        // Vérifier que le prix est correctement formaté
        $this->assertEquals(1299.99, $response->json('price'));
    }

    public function test_product_sorting()
    {
        Product::factory()->create(['name' => 'AAA Product', 'category_id' => $this->category->id]);
        Product::factory()->create(['name' => 'ZZZ Product', 'category_id' => $this->category->id]);
        Product::factory()->create(['name' => 'MMM Product', 'category_id' => $this->category->id]);

        // Test tri ascendant
        $response = $this->getJson('/api/v1/products?sort=name&order=asc');
        $products = $response->json('data');
        $this->assertEquals('AAA Product', $products[0]['name']);
        $this->assertEquals('ZZZ Product', $products[2]['name']);

        // Test tri descendant
        $response = $this->getJson('/api/v1/products?sort=name&order=desc');
        $products = $response->json('data');
        $this->assertEquals('ZZZ Product', $products[0]['name']);
        $this->assertEquals('AAA Product', $products[2]['name']);
    }

    public function test_unauthorized_access()
    {
        // Test sans authentification
        Sanctum::actingAs(null);

        $response = $this->getJson('/api/v1/products');
        $response->assertStatus(401);

        $response = $this->postJson('/api/v1/products', [
            'name' => 'Test',
            'category_id' => $this->category->id,
            'stock_min' => 5,
            'stock_optimal' => 20,
            'current_stock' => 15
        ]);
        $response->assertStatus(401);
    }
}
