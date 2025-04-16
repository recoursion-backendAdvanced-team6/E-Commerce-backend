<?php

use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
// use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;
use App\Http\Controllers\CustomCashierWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Stripe\Webhook;

use App\Models\Product;
use App\Models\Order;
use PhpParser\Node\Stmt\Break_;
use Illuminate\Support\Str;

use App\Mail\OrderConfirmationMail;

// サイトトップ
Route::get('/', [HomeController::class, 'index'])->name('front.home');

// Stripe用リダイレクトルート
Route::get('/home', function () {
    return redirect()->route('front.home');
})->name('home');

// 商品一覧
Route::get('/products', [ProductController::class, 'index'])->name('front.products');
// カテゴリ別商品一覧
Route::get('/products/category/{category}', [ProductController::class, 'category'])->name('front.product.category');
// 商品詳細
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
// Route::post('/stripe/webhook', [CashierWebhookController::class, 'handleWebhook'])->name('cashier.webhook');
Route::post('/stripe/webhook', [CustomCashierWebhookController::class, 'handleWebhook'])->name('cashier.webhook');

// Stripe Webhook/product/create 用ルート
Route::post('/stripe/webhook/product/create', [StripeWebhookController::class, 'handleWebhook'])->name('webhook.product.create');

// 注文完了ページ
Route::get('/order/complete', [OrderController::class, 'complete'])->name('order.complete');

// メール送信
Route::get('/mail', function () {
    // テスト用に、存在する注文を取得（データがない場合は適切に作成してください）
    $order = Order::first();

    if (!$order) {
        return '注文が見つかりません。';
    }

    // テスト送信。受信可能なメールアドレスに変更してください。
    Mail::to('your_email@example.com')->send(new OrderConfirmationMail($order));

    return 'テストメールを送信しました。';
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// マイページ
Route::get('/mypage', [MyPageController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('mypage');

// お気に入りと注文履歴
Route::middleware(['auth'])->group(function () {
    // お気に入り
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');
    Route::post('favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('favorites/{product}', [FavoriteController::class, 'destroy'])->name('favorites.remove');
    // 購入履歴一覧
    Route::get('/orders', [OrderHistoryController::class, 'index'])->name('orders');
    // 購入履歴の詳細ページ
    Route::get('/orders/{order}', [OrderHistoryController::class, 'show'])->name('orders.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::prefix('admin')->name('admin.')->group(function() {
    Route::get('register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);

    Route::get('login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:admin')->name('logout');

    Route::middleware('auth:admin')->group(function() {
        Route::get('dashboard', [DashboardController::class, 'home'])->name('dashboard');
        Route::get('dashboard/products', [DashboardController::class, 'products'])->name('dashboard.products');
        Route::get('dashboard/orders', [DashboardController::class, 'orders'])->name('dashboard.orders');
        Route::resource('products', AdminProductController::class);
    });
});




require __DIR__.'/auth.php';
