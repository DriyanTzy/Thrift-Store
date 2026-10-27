<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ConductorController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SellerController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User / Public Shop Routes (ThriftStore Marketplace)
|--------------------------------------------------------------------------
*/
Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/shop/{userId}', [SellerController::class, 'showStore'])->name('shop.store');

Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
Route::post('/checkout', [OrderController::class, 'store'])->name('orders.store');
Route::post('/api/vouchers/check', [OrderController::class, 'checkVoucher'])->name('api.vouchers.check');
Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');
Route::post('/orders/{order}/pay', [OrderController::class, 'pay'])->name('orders.pay');
Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
Route::get('/orders/receipt/{orderNumber}', [OrderController::class, 'receipt'])->name('orders.receipt');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

/*
|--------------------------------------------------------------------------
| Live Chat Feature Routes (Protected by Auth)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/{conversation}/messages', [ChatController::class, 'sendMessage'])->name('chat.message');
    Route::get('/api/chat/{conversation}/messages', [ChatController::class, 'fetchMessages'])->name('chat.fetch');
    Route::get('/api/chat/unread-count', [ChatController::class, 'unreadCount'])->name('chat.unread');
});

/*
|--------------------------------------------------------------------------
| User Profile & Store Profile Management (1 Email, 2 Separate Profiles)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/user', [ProfileController::class, 'updateUser'])->name('update-user');
    Route::put('/store', [ProfileController::class, 'updateStore'])->name('update-store');
});

/*
|--------------------------------------------------------------------------
| Seller & Store Etalase Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/store', [SellerController::class, 'store'])->name('store');
    Route::get('/products/create', [SellerController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [SellerController::class, 'storeProduct'])->name('products.store');
    Route::post('/products/{product}/stock-movement', [SellerController::class, 'updateStock'])->name('products.stock');
    Route::delete('/products/{product}', [SellerController::class, 'destroyProduct'])->name('products.destroy');
    Route::post('/vouchers', [SellerController::class, 'storeVoucher'])->name('vouchers.store');
    Route::post('/vouchers/{voucher}/toggle', [SellerController::class, 'toggleVoucher'])->name('vouchers.toggle');
    Route::delete('/vouchers/{voucher}', [SellerController::class, 'destroyVoucher'])->name('vouchers.destroy');
});

/*
|--------------------------------------------------------------------------
| Customer / General User Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Dedicated Admin Portal Gate (Pintu Masuk Khusus Administrator)
|--------------------------------------------------------------------------
*/
Route::get('/admin', fn () => redirect()->route('admin.conductor'));
Route::get('/conductor', fn () => redirect()->route('admin.conductor'));
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');
Route::get('/conductor/login', fn () => redirect()->route('admin.login'));

/*
|--------------------------------------------------------------------------
| Admin Panel Routes (Protected by IsAdmin Middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Conductor Control Center (Pusat Kendali Admin)
    Route::get('/conductor', [ConductorController::class, 'index'])->name('conductor');
    Route::post('/conductor/action', [ConductorController::class, 'executeAction'])->name('conductor.action');

    // CRUD Products
    Route::resource('products', AdminProductController::class);

    // CRUD Categories
    Route::resource('categories', AdminCategoryController::class);

    // Orders Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
});
