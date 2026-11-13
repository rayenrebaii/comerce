<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortableShopTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_install_can_be_repeated_without_overwriting_data(): void
    {
        $this->seed(DemoSeeder::class);
        $product = Product::first();
        $product->update(['quantity_available' => 12]);
        $password = User::where('email', 'admin@example.test')->value('password');
        $this->seed(DemoSeeder::class);
        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseCount('produits', 3);
        $this->assertSame(12, $product->fresh()->quantity_available);
        $this->assertSame($password, User::where('email', 'admin@example.test')->value('password'));
    }

    public function test_customer_can_log_in_browse_order_and_view_invoice(): void
    {
        $this->seed(DemoSeeder::class);
        $this->withoutVite();
        $this->get('/login')->assertOk();
        $this->post('/login', ['email' => 'client@example.test', 'password' => 'Demo2024!'])
            ->assertRedirect('/home');
        $this->get('/home')->assertOk();
        $this->get('/client/produits')->assertOk();
        $product = Product::first();
        $this->post('/panier/ajouter', ['product_id' => $product->id, 'quantity' => 2])
            ->assertJson(['success' => true]);
        $this->get('/panier')->assertOk();
        $this->post('/commandes/passer')->assertRedirect('/commandes');
        $this->assertDatabaseCount('commandes', 1);
        $this->assertDatabaseCount('cart_items', 0);
        $this->assertDatabaseHas('commande_product', ['quantity' => 2, 'price' => 75.05]);
        $this->get('/commandes')->assertOk();
        $id = \App\Models\Commande::first()->id;
        $this->get("/commandes/$id/facture")->assertOk();
        $this->post('/logout')->assertRedirect('/login');
    }

    public function test_admin_pages_render_and_registration_works_on_fresh_schema(): void
    {
        $this->seed(DemoSeeder::class);
        $this->withoutVite();
        $this->actingAs(User::where('role', 1)->first());
        foreach (['/dashboard', '/produits', '/categories', '/livraison'] as $url) {
            $this->get($url)->assertOk();
        }
        $this->post('/logout');
        $this->post('/register', [
            'name' => 'Nouveau Client', 'email' => 'nouveau@example.test',
            'password' => 'MonPassword123!', 'password_confirmation' => 'MonPassword123!',
        ])->assertRedirect('/home');
        $this->assertDatabaseHas('users', ['email' => 'nouveau@example.test', 'role' => 0]);
    }
}
