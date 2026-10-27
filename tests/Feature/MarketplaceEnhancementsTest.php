<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Conversation;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MarketplaceEnhancementsTest extends TestCase
{
    use RefreshDatabase;

    protected User $seller;

    protected User $buyer;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seller = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'seller@example.com',
            'store_name' => 'Retro Thrift Store',
            'store_city' => 'Bandung',
            'phone' => '08123456789',
        ]);

        $this->buyer = User::factory()->create([
            'name' => 'Jane Buyer',
            'email' => 'buyer@example.com',
        ]);

        $this->category = Category::create([
            'name' => 'Vintage',
            'slug' => 'vintage',
        ]);
    }

    public function test_out_of_stock_product_hidden_from_catalog_but_accessible_via_direct_link(): void
    {
        $inStockProduct = Product::create([
            'user_id' => $this->seller->id,
            'category_id' => $this->category->id,
            'name' => 'Vintage Denim Jacket',
            'slug' => 'vintage-denim-jacket',
            'price' => 250000,
            'stock' => 3,
            'is_active' => true,
        ]);

        $outOfStockProduct = Product::create([
            'user_id' => $this->seller->id,
            'category_id' => $this->category->id,
            'name' => 'Rare Retro Tee',
            'slug' => 'rare-retro-tee',
            'price' => 120000,
            'stock' => 0,
            'is_active' => true,
        ]);

        // Catalog test: in stock visible, out of stock hidden
        $response = $this->get(route('products.index'));
        $response->assertStatus(200);
        $response->assertSee('Vintage Denim Jacket');
        $response->assertDontSee('Rare Retro Tee');

        // Direct reference test: out of stock accessible via slug with out-of-stock badge
        $detailResponse = $this->get(route('products.show', $outOfStockProduct->slug));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Rare Retro Tee');
        $detailResponse->assertSee('Stok Habis');
    }

    public function test_separate_user_and_store_profile_updating(): void
    {
        $this->actingAs($this->seller);

        // Update User profile (personal)
        $userUpdate = $this->put(route('profile.update-user'), [
            'name' => 'Johnny Thrift Master',
            'phone' => '08999999999',
            'player_tag' => 'THRIFT#999',
            'address' => 'Jl. Veteran No. 10',
            'bio' => 'Passionate vintage collector',
        ]);
        $userUpdate->assertRedirect(route('profile.edit', ['tab' => 'personal']));

        $this->seller->refresh();
        $this->assertEquals('Johnny Thrift Master', $this->seller->name);
        $this->assertEquals('THRIFT#999', $this->seller->player_tag);

        // Update Store profile
        $storeUpdate = $this->put(route('profile.update-store'), [
            'store_name' => 'Vintage Vault Bandung',
            'store_slogan' => 'Best 90s Curation',
            'store_city' => 'Kota Bandung',
            'store_phone' => '08777777777',
            'store_description' => 'Original vintage shirts and pants',
        ]);
        $storeUpdate->assertRedirect(route('profile.edit', ['tab' => 'store']));

        $this->seller->refresh();
        $this->assertEquals('Vintage Vault Bandung', $this->seller->store_name);
        $this->assertEquals('Best 90s Curation', $this->seller->store_slogan);
        $this->assertEquals('Kota Bandung', $this->seller->store_city);
        $this->assertEquals('seller@example.com', $this->seller->email); // Email remains intact
    }

    public function test_chat_creation_and_messaging_between_buyer_and_seller(): void
    {
        $product = Product::create([
            'user_id' => $this->seller->id,
            'category_id' => $this->category->id,
            'name' => 'Vintage Leather Boots',
            'slug' => 'vintage-leather-boots',
            'price' => 450000,
            'stock' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($this->buyer);

        // Initiate chat with product inquiry
        $startChat = $this->get(route('chat.index', [
            'user_id' => $this->seller->id,
            'product_id' => $product->id,
        ]));

        $conversation = Conversation::first();
        $this->assertNotNull($conversation);
        $this->assertEquals($this->buyer->id, $conversation->buyer_id);
        $this->assertEquals($this->seller->id, $conversation->seller_id);

        $startChat->assertRedirect(route('chat.index', ['conversation_id' => $conversation->id]));

        // Fetch messages via API endpoint
        $fetchResponse = $this->getJson(route('chat.fetch', ['conversation' => $conversation->id]));
        $fetchResponse->assertStatus(200);
        $fetchResponse->assertJsonFragment([
            'message' => 'Halo, apakah produk "Vintage Leather Boots" masih tersedia?',
        ]);

        // Seller reply
        $this->actingAs($this->seller);
        $reply = $this->postJson(route('chat.message', ['conversation' => $conversation->id]), [
            'message' => 'Ready kak, mulus no minus!',
        ]);
        $reply->assertStatus(200);

        $this->assertDatabaseHas('chat_messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $this->seller->id,
            'message' => 'Ready kak, mulus no minus!',
        ]);
    }

    public function test_stock_movement_ledger_tracks_in_out_and_balance(): void
    {
        $this->actingAs($this->seller);

        // 1. Seller registers a product with initial stock
        $product = Product::create([
            'user_id' => $this->seller->id,
            'category_id' => $this->category->id,
            'name' => 'Graphic Tee 1994',
            'slug' => 'graphic-tee-1994',
            'price' => 100000,
            'cost_price' => 60000,
            'stock' => 5,
            'is_active' => true,
        ]);

        StockMovement::record(
            product: $product,
            type: 'in',
            qty: 5,
            notes: 'Stok awal produk baru',
            unitCost: 60000,
            unitPrice: 100000,
            userId: $this->seller->id
        );

        $this->assertEquals(5, $product->total_stock_in);
        $this->assertEquals(0, $product->total_stock_out);

        // 2. Restock via updateStock endpoint
        $this->post(route('seller.products.stock', $product->id), [
            'type' => 'in',
            'quantity' => 5,
            'notes' => 'Restok tambahan dari supplier',
            'price' => 110000,
            'cost_price' => 65000,
        ]);

        $product->refresh();
        $this->assertEquals(10, $product->stock);
        $this->assertEquals(110000, $product->price);
        $this->assertEquals(65000, $product->cost_price);
        $this->assertEquals(10, $product->total_stock_in);

        // 3. Stock deduction via order (keluar)
        $order = Order::create([
            'order_number' => 'ORD-TEST-123',
            'user_id' => $this->buyer->id,
            'player_tag' => 'BUYER#123',
            'total_price' => 220000,
            'payment_method' => 'qris',
            'payment_status' => 'paid',
            'order_status' => 'completed',
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => 2,
            'price' => 110000,
            'subtotal' => 220000,
        ]);

        $product->decrement('stock', 2);
        StockMovement::record(
            product: $product,
            type: 'out',
            qty: 2,
            notes: 'Penjualan pesanan #'.$order->order_number,
            unitCost: $product->cost_price,
            unitPrice: 110000,
            userId: $this->buyer->id,
            referenceType: Order::class,
            referenceId: $order->id
        );

        $product->refresh();
        $this->assertEquals(8, $product->stock);
        $this->assertEquals(10, $product->total_stock_in);
        $this->assertEquals(2, $product->total_stock_out);
        $this->assertEquals(8, $product->total_stock_in - $product->total_stock_out);
    }

    public function test_profile_picture_upload_with_5mb_limit(): void
    {
        Storage::fake('public');
        $this->actingAs($this->seller);

        // 1. Upload valid image (4MB = 4096KB <= 5120KB)
        $validFile = UploadedFile::fake()->image('avatar.jpg')->size(4000);
        $response = $this->put(route('profile.update-user'), [
            'name' => 'John Avatar Master',
            'avatar' => $validFile,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('profile.edit', ['tab' => 'personal']));

        $this->seller->refresh();
        $this->assertNotNull($this->seller->avatar);
        Storage::disk('public')->assertExists($this->seller->avatar);

        // 2. Upload file exceeding 5MB (6MB = 6144KB > 5120KB)
        $oversizedFile = UploadedFile::fake()->image('big_avatar.jpg')->size(6000);
        $invalidResponse = $this->put(route('profile.update-user'), [
            'name' => 'John Oversized',
            'avatar' => $oversizedFile,
        ]);

        $invalidResponse->assertSessionHasErrors(['avatar']);
    }

    public function test_product_search_feature_filters_catalog(): void
    {
        $denim = Product::create([
            'user_id' => $this->seller->id,
            'category_id' => $this->category->id,
            'name' => 'Vintage Levi Denim 501',
            'slug' => 'vintage-levi-denim-501',
            'description' => 'Original made in USA dark wash',
            'price' => 350000,
            'stock' => 2,
            'is_active' => true,
        ]);

        $hoodie = Product::create([
            'user_id' => $this->seller->id,
            'category_id' => $this->category->id,
            'name' => 'Champion Reverse Weave Hoodie',
            'slug' => 'champion-reverse-weave-hoodie',
            'description' => 'Heavyweight fleece grey color',
            'price' => 280000,
            'stock' => 3,
            'is_active' => true,
        ]);

        // Search for 'Denim'
        $searchResponse = $this->get(route('products.index', ['q' => 'Denim']));
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Vintage Levi Denim 501');
        $searchResponse->assertDontSee('Champion Reverse Weave Hoodie');
        $searchResponse->assertSee('Hasil pencarian untuk');

        // Search for 'Fleece' (in description)
        $descSearchResponse = $this->get(route('products.index', ['q' => 'Fleece']));
        $descSearchResponse->assertStatus(200);
        $descSearchResponse->assertSee('Champion Reverse Weave Hoodie');
        $descSearchResponse->assertDontSee('Vintage Levi Denim 501');
    }

    public function test_conductor_control_center_accessible_by_admin(): void
    {
        $admin = User::factory()->create([
            'name' => 'Conductor Commander',
            'email' => 'conductor@admin.com',
            'is_admin' => true,
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('admin.conductor'));
        $response->assertStatus(200);
        $response->assertSee('Conductor Control Center');
        $response->assertSee('id="conductor"', false);
        $response->assertSee('Operasi Sistem Cepat');
        $response->assertSee('Gross Revenue');
    }

    public function test_conductor_control_center_forbidden_for_regular_user(): void
    {
        $regularUser = User::factory()->create([
            'name' => 'Regular Buyer',
            'is_admin' => false,
        ]);

        $this->actingAs($regularUser);

        $response = $this->get(route('admin.conductor'));
        $response->assertRedirect('/');
        $response->assertSessionHas('error');
    }

    public function test_conductor_control_center_executes_actions(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $targetUser = User::factory()->create([
            'name' => 'Target User',
            'is_admin' => false,
        ]);

        $this->actingAs($admin);

        // 1. Clear cache action
        $cacheAction = $this->post(route('admin.conductor.action'), [
            'action' => 'clear_cache',
        ]);
        $cacheAction->assertSessionHas('success');

        // 2. Stock audit action
        $auditAction = $this->post(route('admin.conductor.action'), [
            'action' => 'stock_audit',
        ]);
        $auditAction->assertSessionHas('success');

        // 3. Promote target user to admin
        $toggleAction = $this->post(route('admin.conductor.action'), [
            'action' => 'toggle_admin',
            'user_id' => $targetUser->id,
        ]);
        $toggleAction->assertSessionHas('success');

        $targetUser->refresh();
        $this->assertTrue($targetUser->is_admin);
    }

    public function test_unauthenticated_admin_access_redirects_to_dedicated_admin_login_door(): void
    {
        $response = $this->get(route('admin.conductor'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_door_allows_admin_and_rejects_regular_customer(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin_secret@ruanglemari.com',
            'password' => bcrypt('secretAdminPass123'),
            'is_admin' => true,
        ]);

        $customer = User::factory()->create([
            'email' => 'customer_regular@gmail.com',
            'password' => bcrypt('customerPass123'),
            'is_admin' => false,
        ]);

        // 1. Regular customer tries to log in via admin door -> REJECTED
        $customerAttempt = $this->post(route('admin.login.submit'), [
            'email' => 'customer_regular@gmail.com',
            'password' => 'customerPass123',
        ]);
        $customerAttempt->assertSessionHasErrors(['email']);
        $this->assertGuest();

        // 2. Admin logs in via admin door -> ACCEPTED
        $adminAttempt = $this->post(route('admin.login.submit'), [
            'email' => 'admin_secret@ruanglemari.com',
            'password' => 'secretAdminPass123',
        ]);
        $adminAttempt->assertRedirect('/admin/conductor');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_customer_door_allows_customer_and_rejects_admin(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin_door_test@ruanglemari.com',
            'password' => bcrypt('secretAdminPass123'),
            'is_admin' => true,
        ]);

        $customer = User::factory()->create([
            'email' => 'customer_door_test@gmail.com',
            'password' => bcrypt('customerPass123'),
            'is_admin' => false,
        ]);

        // 1. Admin tries to log in via customer door -> REJECTED
        $adminAttempt = $this->post(route('login'), [
            'email' => 'admin_door_test@ruanglemari.com',
            'password' => 'secretAdminPass123',
        ]);
        $adminAttempt->assertSessionHasErrors(['email']);
        $this->assertGuest();

        // 2. Customer logs in via customer door -> ACCEPTED
        $customerAttempt = $this->post(route('login'), [
            'email' => 'customer_door_test@gmail.com',
            'password' => 'customerPass123',
        ]);
        $customerAttempt->assertRedirect('/');
        $this->assertAuthenticatedAs($customer);
    }

    public function test_conductor_admin_login_with_id_and_password(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'conductor@ruanglemari.com'],
            [
                'name' => 'Conductor Commander',
                'player_tag' => 'conductor',
                'password' => bcrypt('takutyadek'),
                'is_admin' => true,
            ]
        );

        // Login using login_id = 'conductor' and password = 'takutyadek'
        $response = $this->post(route('admin.login.submit'), [
            'login_id' => 'conductor',
            'password' => 'takutyadek',
        ]);

        $response->assertRedirect('/admin/conductor');
        $this->assertAuthenticatedAs($admin);

        // Test Conductor dashboard displays 100% - 12% business logic
        $dashboard = $this->get(route('admin.conductor'));
        $dashboard->assertStatus(200);
        $dashboard->assertSee('Struktur Pendapatan — Bisnis Logic');
        $dashboard->assertSee('Profit Aplikator');
        $dashboard->assertSee('Pajak');
        $dashboard->assertSee('Operasional');
        $dashboard->assertSee('88%');

        // Test admin logout redirects to admin login door
        $logoutResponse = $this->post(route('logout'));
        $logoutResponse->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_public_store_view_displays_store_profile_picture_and_navbar_menu(): void
    {
        // 1. Seller without uploaded logo displays initial / store initial
        $storeView = $this->get(route('shop.store', $this->seller->id));
        $storeView->assertStatus(200);
        $storeView->assertSee($this->seller->store_name);

        // 2. Seller with uploaded store_logo displays the image tag
        $this->seller->store_logo = 'logos/my_store_logo.png';
        $this->seller->save();

        $storeViewWithLogo = $this->get(route('shop.store', $this->seller->id));
        $storeViewWithLogo->assertStatus(200);
        $storeViewWithLogo->assertSee('storage/logos/my_store_logo.png');
        $storeViewWithLogo->assertSee('PP Toko');

        // 3. Authenticated user sees the upgraded anti-flicker dropdown trigger in navbar
        $this->actingAs($this->seller);
        $homeView = $this->get(route('products.index'));
        $homeView->assertStatus(200);
        $homeView->assertSee('rp-user-trigger-btn', false);
        $homeView->assertSee('id="user-nav-dropdown"', false);
    }

    public function test_navbar_search_can_find_regular_users_and_stores_but_excludes_admin(): void
    {
        // 1. Regular seller store
        $regularSeller = User::create([
            'name' => 'Budi Thrifter',
            'email' => 'budi_vintage@gmail.com',
            'store_name' => 'Budi Vintage Vault',
            'player_tag' => 'budi_vintage',
            'password' => bcrypt('password123'),
            'is_admin' => false,
        ]);

        // 2. Secret Admin user
        $secretAdmin = User::create([
            'name' => 'Super Conductor Admin',
            'email' => 'admin_secret_search@ruanglemari.com',
            'store_name' => 'Admin Secret Store',
            'player_tag' => 'conductor_boss',
            'password' => bcrypt('password123'),
            'is_admin' => true,
        ]);

        // Search for 'Budi' -> Regular user found
        $responseBudi = $this->get(route('products.index', ['q' => 'Budi']));
        $responseBudi->assertStatus(200);
        $responseBudi->assertSee('Budi Vintage Vault');
        $responseBudi->assertSee('Kunjungi Toko');

        // Search for 'Conductor' or admin email -> Admin MUST NOT be found
        $responseAdmin = $this->get(route('products.index', ['q' => 'conductor_boss']));
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertDontSee('Super Conductor Admin');
        $responseAdmin->assertDontSee('Admin Secret Store');
    }
}
