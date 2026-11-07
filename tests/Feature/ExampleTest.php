<?php

namespace Tests\Feature;

use App\Models\OrderLocation;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $this->get('/')->assertRedirect('/products');
        $this->get('/products')->assertRedirect(route('login'));
        $this->get('/dashboard')->assertRedirect(route('login'));
        $this->get('/map')->assertRedirect(route('login'));
        $this->get('/analytics')->assertRedirect(route('login'));
        $this->get('/login')->assertOk()->assertSee('Bintang Agung UMKM');
    }

    public function test_sidebar_pages_and_products_crud_with_stock_works(): void
    {
        $user = User::create([
            'name' => 'Admin UMKM',
            'email' => 'admin@umkm.com',
            'password' => Hash::make('password123'),
        ]);

        $loginResponse = $this->post(route('login.attempt'), [
            'email' => 'admin@umkm.com',
            'password' => 'password123',
        ]);

        $loginResponse->assertRedirect(route('products.index'));
        $this->assertAuthenticatedAs($user);

        $createResponse = $this->post(route('products.store'), [
            'name' => 'Keripik Pisang Original',
            'category' => 'Makanan',
            'description' => 'Renyah dan gurih',
            'price' => 15000,
            'stock' => 478,
        ]);

        $createResponse->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'Keripik Pisang Original',
            'stock' => 478,
        ]);

        $product = Product::first();

        $indexResponse = $this->get(route('products.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('Bintang Agung UMKM');
        $indexResponse->assertSee('bi-shop-window', false);
        $indexResponse->assertSee('Keripik Pisang Original');
        $indexResponse->assertSee('478 pcs');

        $dashboardResponse = $this->get(route('umkm.dashboard'));
        $dashboardResponse->assertOk()->assertSee('Dashboard');

        $analyticsResponse = $this->get(route('umkm.analytics'));
        $analyticsResponse->assertOk()->assertSee('Analitik Produk');

        $updateResponse = $this->put(route('products.update', $product), [
            'name' => 'Keripik Pisang Original',
            'category' => 'Makanan',
            'description' => 'Renyah dan gurih',
            'price' => 16000,
            'stock' => 500,
        ]);

        $updateResponse->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'price' => 16000,
            'stock' => 500,
        ]);

        $deleteResponse = $this->delete(route('products.destroy', $product));
        $deleteResponse->assertRedirect(route('products.index'));
        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);

        $logoutResponse = $this->post(route('logout'));
        $logoutResponse->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_leaflet_map_locations_and_connected_points_works(): void
    {
        $user = User::create([
            'name' => 'Admin UMKM',
            'email' => 'admin@umkm.com',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($user);

        $product = Product::create([
            'name' => 'Kopi Robusta UMKM',
            'category' => 'Minuman',
            'description' => 'Bubuk kopi robusta murni pilihan petani lokal.',
            'price' => 20000,
            'stock' => 357,
        ]);

        $storeMapResponse = $this->post(route('map.store'), [
            'product_id' => $product->id,
            'customer_name' => 'Kedai Kopi Malioboro',
            'quantity' => 30,
            'address' => 'Jl. Malioboro No. 40, Yogyakarta',
            'latitude' => -7.7925764,
            'longitude' => 110.3658430,
        ]);

        $storeMapResponse->assertRedirect(route('map.index'));
        $this->assertDatabaseHas('orders_locations', [
            'product_id' => $product->id,
            'customer_name' => 'Kedai Kopi Malioboro',
            'quantity' => 30,
        ]);

        $mapPageResponse = $this->get(route('map.index'));
        $mapPageResponse->assertOk();
        $mapPageResponse->assertSee('Kopi Robusta UMKM');
        $mapPageResponse->assertSee('Bubuk kopi robusta murni pilihan petani lokal.');
        $mapPageResponse->assertSee('connectionModeSelect', false);

        $location = OrderLocation::first();
        $deleteMapResponse = $this->delete(route('map.destroy', $location));
        $deleteMapResponse->assertRedirect(route('map.index'));
        $this->assertDatabaseMissing('orders_locations', [
            'id' => $location->id,
        ]);
    }
}
