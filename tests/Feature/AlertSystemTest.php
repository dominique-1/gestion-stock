<?php

namespace Tests\Feature;

use App\Models\Alert;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AlertSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'alert@example.com',
            'name' => 'Alert Manager'
        ]);
        Sanctum::actingAs($this->user);

        $this->category = Category::factory()->create(['name' => 'Test Category']);
        
        $this->product = Product::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'Test Product',
            'current_stock' => 5,
            'stock_min' => 10,
            'stock_optimal' => 50
        ]);
    }

    public function test_can_view_alerts_list()
    {
        // Créer des alertes de test
        Alert::factory()->count(3)->create([
            'product_id' => $this->product->id,
            'level' => 'warning',
            'is_read' => false
        ]);

        Alert::factory()->count(2)->create([
            'product_id' => $this->product->id,
            'level' => 'critical',
            'is_read' => true
        ]);

        $response = $this->getJson('/api/v1/alerts');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data',
                    'current_page',
                    'per_page',
                    'total'
                ]);

        $this->assertEquals(5, $response->json('total'));
    }

    public function test_can_filter_unread_alerts()
    {
        Alert::factory()->count(3)->create([
            'product_id' => $this->product->id,
            'level' => 'warning',
            'is_read' => false
        ]);

        Alert::factory()->count(2)->create([
            'product_id' => $this->product->id,
            'level' => 'critical',
            'is_read' => true
        ]);

        $response = $this->getJson('/api/v1/alerts?unread=true');

        $response->assertStatus(200);
        $this->assertEquals(3, $response->json('total'));

        foreach ($response->json('data') as $alert) {
            $this->assertFalse($alert['is_read']);
        }
    }

    public function test_can_filter_alerts_by_level()
    {
        Alert::factory()->count(3)->create([
            'product_id' => $this->product->id,
            'level' => 'warning'
        ]);

        Alert::factory()->count(2)->create([
            'product_id' => $this->product->id,
            'level' => 'critical'
        ]);

        Alert::factory()->count(1)->create([
            'product_id' => $this->product->id,
            'level' => 'info'
        ]);

        $response = $this->getJson('/api/v1/alerts?level=critical');

        $response->assertStatus(200);
        $this->assertEquals(2, $response->json('total'));

        foreach ($response->json('data') as $alert) {
            $this->assertEquals('critical', $alert['level']);
        }
    }

    public function test_can_filter_alerts_by_type()
    {
        Alert::factory()->count(3)->create([
            'product_id' => $this->product->id,
            'type' => 'low_stock'
        ]);

        Alert::factory()->count(2)->create([
            'product_id' => $this->product->id,
            'type' => 'expiring_soon'
        ]);

        Alert::factory()->count(1)->create([
            'product_id' => $this->product->id,
            'type' => 'overstock'
        ]);

        $response = $this->getJson('/api/v1/alerts?type=low_stock');

        $response->assertStatus(200);
        $this->assertEquals(3, $response->json('total'));

        foreach ($response->json('data') as $alert) {
            $this->assertEquals('low_stock', $alert['type']);
        }
    }

    public function test_can_mark_alert_as_read()
    {
        $alert = Alert::factory()->create([
            'product_id' => $this->product->id,
            'level' => 'warning',
            'is_read' => false
        ]);

        $response = $this->postJson("/api/v1/alerts/{$alert->id}/read");

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Alert marked as read'
                ]);

        $alert->refresh();
        $this->assertTrue($alert->is_read);
        $this->assertNotNull($alert->read_at);
    }

    public function test_can_delete_alert()
    {
        $alert = Alert::factory()->create([
            'product_id' => $this->product->id,
            'level' => 'warning'
        ]);

        $response = $this->deleteJson("/api/v1/alerts/{$alert->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('alerts', [
            'id' => $alert->id
        ]);
    }

    public function test_alert_product_relationship()
    {
        $alert = Alert::factory()->create([
            'product_id' => $this->product->id,
            'message' => 'Stock faible pour Test Product'
        ]);

        $response = $this->getJson('/api/v1/alerts');

        $response->assertStatus(200);
        
        $alertData = $response->json('data.0');
        $this->assertEquals($this->product->name, $alertData['product']['name']);
        $this->assertEquals($this->product->barcode, $alertData['product']['barcode']);
        $this->assertEquals($this->product->current_stock, $alertData['product']['current_stock']);
    }

    public function test_alert_creator_relationship()
    {
        $alert = Alert::factory()->create([
            'product_id' => $this->product->id,
            'created_by' => $this->user->id
        ]);

        $response = $this->getJson('/api/v1/alerts');

        $response->assertStatus(200);
        
        $alertData = $response->json('data.0');
        $this->assertEquals($this->user->name, $alertData['creator']['name']);
        $this->assertEquals($this->user->email, $alertData['creator']['email']);
    }

    public function test_alert_ordering()
    {
        // Créer des alertes à différents moments
        $alert1 = Alert::factory()->create([
            'product_id' => $this->product->id,
            'created_at' => now()->subHours(3)
        ]);

        $alert2 = Alert::factory()->create([
            'product_id' => $this->product->id,
            'created_at' => now()->subHours(1)
        ]);

        $alert3 = Alert::factory()->create([
            'product_id' => $this->product->id,
            'created_at' => now()
        ]);

        $response = $this->getJson('/api/v1/alerts');

        $response->assertStatus(200);
        
        $alerts = $response->json('data');
        $this->assertEquals($alert3->id, $alerts[0]['id']); // Plus récent en premier
        $this->assertEquals($alert1->id, $alerts[2]['id']); // Plus ancien en dernier
    }

    public function test_low_stock_alert_creation()
    {
        // Simuler la création d'une alerte de stock faible
        $alert = Alert::factory()->create([
            'product_id' => $this->product->id,
            'type' => 'low_stock',
            'level' => 'warning',
            'message' => "Stock faible pour {$this->product->name}: {$this->product->current_stock} unités (minimum: {$this->product->stock_min})",
            'is_read' => false
        ]);

        $this->assertInstanceOf(Alert::class, $alert);
        $this->assertEquals('low_stock', $alert->type);
        $this->assertEquals('warning', $alert->level);
        $this->assertStringContainsString('Stock faible', $alert->message);
        $this->assertFalse($alert->is_read);
    }

    public function test_critical_stock_alert_creation()
    {
        // Produit avec stock critique
        $criticalProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'current_stock' => 2,
            'stock_min' => 10
        ]);

        $alert = Alert::factory()->create([
            'product_id' => $criticalProduct->id,
            'type' => 'critical_stock',
            'level' => 'critical',
            'message' => "Rupture imminente pour {$criticalProduct->name}: {$criticalProduct->current_stock} unités seulement!",
            'is_read' => false
        ]);

        $this->assertEquals('critical', $alert->level);
        $this->assertEquals('critical_stock', $alert->type);
        $this->assertStringContainsString('Rupture imminente', $alert->message);
    }

    public function test_expiring_soon_alert_creation()
    {
        $expiringProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'expires_at' => now()->addDays(5)
        ]);

        $alert = Alert::factory()->create([
            'product_id' => $expiringProduct->id,
            'type' => 'expiring_soon',
            'level' => 'warning',
            'message' => "Produit expirant bientôt: {$expiringProduct->name} expire dans 5 jours",
            'is_read' => false
        ]);

        $this->assertEquals('expiring_soon', $alert->type);
        $this->assertEquals('warning', $alert->level);
        $this->assertStringContainsString('expirant bientôt', $alert->message);
    }

    public function test_overstock_alert_creation()
    {
        $overstockProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'current_stock' => 150,
            'stock_optimal' => 50
        ]);

        $alert = Alert::factory()->create([
            'product_id' => $overstockProduct->id,
            'type' => 'overstock',
            'level' => 'info',
            'message' => "Surstock détecté pour {$overstockProduct->name}: {$overstockProduct->current_stock} unités (optimal: {$overstockProduct->stock_optimal})",
            'is_read' => false
        ]);

        $this->assertEquals('overstock', $alert->type);
        $this->assertEquals('info', $alert->level);
        $this->assertStringContainsString('Surstock', $alert->message);
    }

    public function test_multiple_filters_combination()
    {
        Alert::factory()->count(2)->create([
            'product_id' => $this->product->id,
            'type' => 'low_stock',
            'level' => 'warning',
            'is_read' => false
        ]);

        Alert::factory()->count(1)->create([
            'product_id' => $this->product->id,
            'type' => 'low_stock',
            'level' => 'critical',
            'is_read' => false
        ]);

        Alert::factory()->count(1)->create([
            'product_id' => $this->product->id,
            'type' => 'low_stock',
            'level' => 'warning',
            'is_read' => true
        ]);

        Alert::factory()->count(1)->create([
            'product_id' => $this->product->id,
            'type' => 'expiring_soon',
            'level' => 'warning',
            'is_read' => false
        ]);

        // Filtrer: non lues + niveau warning + type low_stock
        $response = $this->getJson('/api/v1/alerts?unread=true&level=warning&type=low_stock');

        $response->assertStatus(200);
        $this->assertEquals(2, $response->json('total'));

        foreach ($response->json('data') as $alert) {
            $this->assertFalse($alert['is_read']);
            $this->assertEquals('warning', $alert['level']);
            $this->assertEquals('low_stock', $alert['type']);
        }
    }

    public function test_alert_pagination()
    {
        Alert::factory()->count(25)->create([
            'product_id' => $this->product->id
        ]);

        $response = $this->getJson('/api/v1/alerts?per_page=10');

        $response->assertStatus(200);
        $this->assertEquals(25, $response->json('total'));
        $this->assertEquals(10, $response->json('per_page'));
        $this->assertEquals(1, $response->json('current_page'));
        $this->assertCount(10, $response->json('data'));
    }

    public function test_alert_timestamps()
    {
        $alert = Alert::factory()->create([
            'product_id' => $this->product->id,
            'is_read' => false
        ]);

        $this->assertNotNull($alert->created_at);
        $this->assertNull($alert->read_at);

        // Marquer comme lue
        $alert->markAsRead();
        $alert->refresh();

        $this->assertNotNull($alert->read_at);
        $this->assertTrue($alert->read_at->greaterThan($alert->created_at));
    }

    public function test_alert_validation()
    {
        $invalidData = [
            'product_id' => 999, // Produit inexistant
            'type' => 'invalid_type',
            'level' => 'invalid_level',
            'message' => '' // Message vide
        ];

        $response = $this->postJson('/api/v1/alerts', $invalidData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['product_id', 'type', 'level', 'message']);
    }

    public function test_alert_mass_marking_as_read()
    {
        $alerts = Alert::factory()->count(5)->create([
            'product_id' => $this->product->id,
            'is_read' => false
        ]);

        // Marquer chaque alerte comme lue individuellement
        foreach ($alerts as $alert) {
            $this->postJson("/api/v1/alerts/{$alert->id}/read")->assertStatus(200);
        }

        // Vérifier que toutes sont lues
        $response = $this->getJson('/api/v1/alerts?unread=true');
        $this->assertEquals(0, $response->json('total'));

        $response = $this->getJson('/api/v1/alerts');
        foreach ($response->json('data') as $alert) {
            $this->assertTrue($alert['is_read']);
        }
    }

    public function test_alert_cleanup_old_read_alerts()
    {
        // Créer des anciennes alertes lues
        Alert::factory()->count(3)->create([
            'product_id' => $this->product->id,
            'is_read' => true,
            'read_at' => now()->subMonths(2)
        ]);

        // Créer des alertes récentes
        Alert::factory()->count(2)->create([
            'product_id' => $this->product->id,
            'is_read' => true,
            'read_at' => now()->subDays(2)
        ]);

        // Simuler un nettoyage (à implémenter dans une tâche planifiée)
        $oldAlerts = Alert::where('is_read', true)
            ->where('read_at', '<', now()->subMonth())
            ->get();

        $this->assertEquals(3, $oldAlerts->count());
    }
}
