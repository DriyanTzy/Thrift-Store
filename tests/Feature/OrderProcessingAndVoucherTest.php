<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OrderProcessingAndVoucherTest extends TestCase
{
    use RefreshDatabase;

    protected User $seller;

    protected User $buyer;

    protected Category $category;

    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seller = User::factory()->create([
            'name' => 'Seller Owner',
            'email' => 'seller@store.test',
            'store_name' => 'Store Lemari',
            'store_city' => 'Jakarta',
        ]);

        $this->buyer = User::factory()->create([
            'name' => 'Buyer Tester',
            'email' => 'buyer@test.test',
        ]);

        $this->category = Category::create([
            'name' => 'Outerwear',
            'slug' => 'outerwear',
        ]);

        $this->product = Product::create([
            'category_id' => $this->category->id,
            'user_id' => $this->seller->id,
            'name' => 'Vintage Denim Jacket',
            'slug' => 'vintage-denim-jacket',
            'price' => 200000,
            'stock' => 5,
            'is_active' => true,
        ]);
    }

    public function test_checkout_creates_order_in_processing_status(): void
    {
        $this->actingAs($this->buyer);

        $response = $this->post(route('orders.store'), [
            'items' => json_encode([
                ['id' => $this->product->id, 'qty' => 2],
            ]),
            'player_tag' => 'buyer@test.test',
            'payment_method' => 'qris',
        ]);

        $response->assertRedirect(route('orders.history'));
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->buyer->id,
            'payment_status' => 'pending',
            'order_status' => 'processing',
            'total_price' => 400000,
        ]);

        // Stock must be reserved/deducted
        $this->assertEquals(3, $this->product->fresh()->stock);
    }

    public function test_voucher_promo_can_be_checked_and_applied(): void
    {
        $voucher = Voucher::create([
            'code' => 'DISKON50',
            'name' => 'Diskon Aplikator 50K',
            'type' => 'platform',
            'discount_type' => 'fixed',
            'discount_value' => 50000,
            'min_spend' => 100000,
            'quota' => 10,
            'used_count' => 0,
            'is_active' => true,
        ]);

        // Check via API
        $response = $this->postJson(route('api.vouchers.check'), [
            'code' => 'DISKON50',
            'subtotal' => 200000,
            'seller_ids' => [$this->seller->id],
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'code' => 'DISKON50',
            'discount_amount' => 50000,
            'final_total' => 150000,
        ]);

        // Submit checkout with voucher
        $this->actingAs($this->buyer);
        $this->post(route('orders.store'), [
            'items' => json_encode([
                ['id' => $this->product->id, 'qty' => 1],
            ]),
            'player_tag' => 'buyer@test.test',
            'payment_method' => 'bca',
            'voucher_code' => 'DISKON50',
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->buyer->id,
            'voucher_code' => 'DISKON50',
            'discount_amount' => 50000,
            'total_price' => 150000,
            'order_status' => 'processing',
        ]);

        $this->assertEquals(1, $voucher->fresh()->used_count);
    }

    public function test_order_can_be_paid_and_completed(): void
    {
        $this->actingAs($this->buyer);

        $order = Order::create([
            'order_number' => 'RP-TEST-001',
            'user_id' => $this->buyer->id,
            'player_tag' => 'buyer@test.test',
            'total_price' => 200000,
            'payment_method' => 'bca',
            'payment_status' => 'pending',
            'order_status' => 'processing',
        ]);

        $response = $this->post(route('orders.pay', $order->id));
        $response->assertRedirect(route('orders.history'));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'paid',
            'order_status' => 'completed',
        ]);
    }

    public function test_order_can_be_cancelled_and_stock_is_restored(): void
    {
        $this->actingAs($this->buyer);

        // Deduct 2 stock initially
        $this->product->update(['stock' => 3]);

        $order = Order::create([
            'order_number' => 'RP-CANCEL-001',
            'user_id' => $this->buyer->id,
            'player_tag' => 'buyer@test.test',
            'total_price' => 400000,
            'payment_method' => 'bca',
            'payment_status' => 'pending',
            'order_status' => 'processing',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'price' => 200000,
            'quantity' => 2,
            'subtotal' => 400000,
        ]);

        $response = $this->post(route('orders.cancel', $order->id));
        $response->assertRedirect(route('orders.history'));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'failed',
            'order_status' => 'cancelled',
        ]);

        // Stock restored from 3 back to 5
        $this->assertEquals(5, $this->product->fresh()->stock);

        // Stock movement ledger recorded
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $this->product->id,
            'type' => 'in',
            'quantity' => 2,
            'reference_type' => 'order_cancel',
        ]);
    }

    public function test_category_image_can_be_updated(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'email' => 'admin@conductor.test',
            'is_admin' => true,
        ]);

        $this->actingAs($admin);

        $file = UploadedFile::fake()->image('category_banner.jpg', 600, 400);

        $response = $this->put(route('admin.categories.update', $this->category->id), [
            'name' => 'Outerwear Vintage Updated',
            'image' => $file,
        ]);

        $response->assertRedirect(route('admin.categories.index'));

        $updatedCat = $this->category->fresh();
        $this->assertEquals('Outerwear Vintage Updated', $updatedCat->name);
        $this->assertNotNull($updatedCat->image);
        Storage::disk('public')->assertExists($updatedCat->image);
    }

    public function test_payment_information_shows_normal_and_pending_finishing_counts(): void
    {
        $this->actingAs($this->buyer);

        // State 1: No orders -> Normal
        $response = $this->get(route('orders.history'));
        $response->assertOk();
        $response->assertSee('Status Pembayaran Normal');

        // State 2: Create a pending order
        $order = Order::create([
            'order_number' => 'RP-PAYINFO-001',
            'user_id' => $this->buyer->id,
            'player_tag' => 'buyer@test.test',
            'total_price' => 200000,
            'payment_method' => 'bca',
            'payment_status' => 'pending',
            'order_status' => 'processing',
        ]);

        $response = $this->get(route('orders.history'));
        $response->assertOk();
        $response->assertSee('Menunggu Finishing');
        $response->assertSee('Terdapat 1 Pesanan Menunggu Finishing Pembayaran');
        $response->assertSee('Rp 200.000');
    }
}
