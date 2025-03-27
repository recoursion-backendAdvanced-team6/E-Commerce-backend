<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;

use App\Models\Product;

Route::get('/', [HomeController::class, 'index'])->name('front.home');
# Stripe用リダイレクトルート
Route::get('/home', function () {
    return redirect()->route('front.home');
})->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('front.products');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('front.product.show');

// カート関連
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// 配送先入力ページ
Route::get('/checkout/shipping', [CheckoutController::class, 'showShippingForm'])->name('checkout.shipping');
Route::post('/checkout/shipping', [CheckoutController::class, 'storeShipping'])->name('checkout.shipping.store');

// 支払い・最終確認ページ
Route::get('/checkout/payment', [CheckoutController::class, 'showPaymentForm'])->name('checkout.payment');
Route::post('/checkout/payment', [CheckoutController::class, 'processPayment'])->name('checkout.payment.process');

// Stripe Webhook 用ルート（Laravel Cashier の WebhookController を利用）
Route::post('/stripe/webhook', [CashierWebhookController::class, 'handleWebhook'])->name('cashier.webhook');

// 注文完了ページ
Route::get('/order/complete', function () {
    return view('checkout.complete');
})->name('order.complete');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::prefix('admin')->name('admin.')->group(function() {
    Route::get('dashboard', [DashboardController::class, 'home'])->name('dashboard');
    Route::get('dashboard/products', [DashboardController::class, 'products'])->name('dashboard.products');
    Route::get('dashboard/orders', [DashboardController::class, 'orders'])->name('dashboard.orders');
    Route::get('dashboard/{page?}/{subpage?}', [DashboardController::class, 'home'])->name('dashboard.page.subpage');
});


require __DIR__.'/auth.php';
