<?php

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
use Illuminate\Support\Facades\Log;
// use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;
use App\Http\Controllers\CustomCashierWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Stripe\Webhook;

use App\Models\Product;
use PhpParser\Node\Stmt\Break_;
use Illuminate\Support\Str;

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
    Route::get('dashboard', [DashboardController::class, 'home'])->name('dashboard');
    Route::get('dashboard/products', [DashboardController::class, 'products'])->name('dashboard.products');
    Route::get('dashboard/orders', [DashboardController::class, 'orders'])->name('dashboard.orders');
    Route::get('dashboard/{page?}/{subpage?}', [DashboardController::class, 'home'])->name('dashboard.page.subpage');
});

/*
Route::post('/webhook/stripe', function(Request $request) {

    // webookの検証を行う
    $payload = $request->getContent();
    $sig_header = $request->header('Stripe-Signature');
    $secret = env("STRIPE_WEBHOOK_SECRET");

    try{
        $event = Webhook::constructEvent(
            $payload, $sig_header, $secret
        );

    }catch (\UnexpectedValueException $e){
        return response('Invalid Payload', 400);
    }catch (\Stripe\Exception\SignatureVerificationException $e){
        return response('Invalid Signature', 400);
    }catch(Exception $e){
        return response('webhook error', 400);
    }


    $data = $event->data->object;

    // 重複しているか確認し、重複している場合はduplicateを返す
    if(Cache::has('webhook_stripe_' . $data->id)){
        return response()->json(['status' => 'duplicate']);
    }
    Cache::put('webhook_stripe_' . $data->id, true, 60);



    switch ($event->type) {

        // priceをtype: price.createdから取得
        case  'price.created':
            $product = Product::where('stripe_product_id', $data->product)->first();
            if(!$product){
                Product::updateOrCreate(
                    ['stripe_product_id' => $data->product],
                    [
                        'stripe_price_id' => $data->id,
                        'price' => $data->unit_amount,
                        'image_url' => null,
                        'title' => '',
                        'description' => '',
                        'published_date' => null,
                        'status' => 'draft',
                        'inventory' => 0,
                        'is_digital' => false,
                    ],
                );
            }else {
                Product::updateOrCreate(
                    ['stripe_product_id' => $data->product],
                    [
                        'stripe_price_id' => $data->id,
                        'price' => $data->unit_amount,
                    ],
                );
            }
            break;

        // 作成されたproductをDBに登録
        case 'product.created':
            $product = Product::where('stripe_product_id', $data->id)->first();
            if(!$product){
                Product::updateOrCreate(
                    ['stripe_product_id' => $data->id],
                    [
                        'image_url' => $data->images[0] ?? null,
                        'title' => $data->name,
                        'description' => $data->description,
                        'published_date' => null,
                        'status' => 'draft',
                        'price' => 0,
                        'inventory' => 0,
                        'is_digital' => false,
                    ],
                );

            }else {
                Product::updateOrCreate(
                    ['stripe_product_id' => $data->id],
                    [
                        'image_url' => $data->images[0] ?? null,
                        'title' => $data->name,
                        'description' => $data->description,
                    ],
                );
            }
            break;
        
        
        case 'product.deleted':
            $stripeProductId = $data->id;
            $product = Product::where('stripe_product_id', $stripeProductId)->first();
            if($product){
                $product->delete();
            }

            break;
    }


    return response('success', 200);
});
*/


require __DIR__.'/auth.php';
