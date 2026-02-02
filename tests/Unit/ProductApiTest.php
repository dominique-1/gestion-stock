<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer une catégorie de test
        $this->category = Category::factory()->create([
            'name' => 'Informatique',
            'description' => 'Produits informatiques'
        ]);
    }

    public function test_can_create_product()
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
            'current_stock' => 15
        ];

        $product = Product::create($productData);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('Laptop Pro 15"', $product->name);
        $this->assertEquals(1299.99, $product->price);
        $this->assertEquals($this->category->id, $product->category_id);
    }

    public function test_product_low_stock_scope()
    {
        // Créer un produit avec stock faible
        $lowStockProduct = Product::factory()->create([
            'name' => 'Produit stock faible',
            'current_stock' => 3,
            'stock_min' => 5
        ]);

        // Créer un produit avec stock normal
        $normalStockProduct = Product::factory()->create([
            'name' => 'Produit stock normal',
            'current_stock' => 10,
            'stock_min' => 5
        ]);

        $lowStockProducts = Product::lowStock()->get();

        $this->assertCount(1, $lowStockProducts);
        $this->assertEquals($lowStockProduct->id, $lowStockProducts->first()->id);
    }

    public function test_product_overstock_scope()
    {
        // Créer un produit en surstock
        $overstockProduct = Product::factory()->create([
            'name' => 'Produit surstock',
            'current_stock' => 25,
            'stock_optimal' => 20
        ]);

        // Créer un produit avec stock normal
        $normalStockProduct = Product::factory()->create([
            'name' => 'Produit stock normal',
            'current_stock' => 15,
            'stock_optimal' => 20
        ]);

        $overstockProducts = Product::overstock()->get();

        $this->assertCount(1, $overstockProducts);
        $this->assertEquals($overstockProduct->id, $overstockProducts->first()->id);
    }

    public function test_product_expiring_soon_scope()
    {
        // Créer un produit qui expire bientôt
        $expiringProduct = Product::factory()->create([
            'name' => 'Produit expiration proche',
            'expires_at' => now()->addDays(5)
        ]);

        // Créer un produit avec expiration lointaine
        $normalProduct = Product::factory()->create([
            'name' => 'Produit expiration normale',
            'expires_at' => now()->addDays(30)
        ]);

        $expiringProducts = Product::expiringSoon()->get();

        $this->assertCount(1, $expiringProducts);
        $this->assertEquals($expiringProduct->id, $expiringProducts->first()->id);
    }

    public function test_product_search_by_name()
    {
        $product = Product::factory()->create([
            'name' => 'Laptop Gaming Pro',
            'barcode' => '123456789'
        ]);

        $searchResults = Product::where('name', 'like', '%gaming%')->get();

        $this->assertCount(1, $searchResults);
        $this->assertEquals($product->id, $searchResults->first()->id);
    }

    public function test_product_search_by_barcode()
    {
        $product = Product::factory()->create([
            'name' => 'Moniteur 27"',
            'barcode' => '987654321'
        ]);

        $searchResults = Product::where('barcode', '987654321')->get();

        $this->assertCount(1, $searchResults);
        $this->assertEquals($product->id, $searchResults->first()->id);
    }

    public function test_product_category_relationship()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id
        ]);

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals($this->category->name, $product->category->name);
    }

    public function test_product_validation_rules()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        // Tentative de créer un produit sans nom requis
        Product::create([
            'description' => 'Description test',
            'stock_min' => 5,
            'stock_optimal' => 20,
            'current_stock' => 15
        ]);
    }

    public function test_product_stock_calculation()
    {
        $product = Product::factory()->create([
            'current_stock' => 15,
            'stock_min' => 5,
            'stock_optimal' => 20
        ]);

        // Vérifier que le stock actuel est correct
        $this->assertEquals(15, $product->current_stock);
        
        // Vérifier que le produit n'est pas en stock faible
        $this->assertFalse($product->current_stock < $product->stock_min);
        
        // Vérifier que le produit n'est pas en surstock
        $this->assertFalse($product->current_stock > $product->stock_optimal);
    }
}
