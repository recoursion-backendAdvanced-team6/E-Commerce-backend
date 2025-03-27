<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Stripe\Webhook;
use App\Models\Product;

Route::get('/', [HomeController::class, 'index'])->name('front.home');
Route::get('/products', [ProductController::class, 'index'])->name('front.products');

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

    // priceをtype: price.createdから取得
    $price = 0;
    if($event->type === 'price.created') {
        $price = $data->unit_amount;
    };


    switch ($event->type) {
        // 作成されたproductをDBに登録
        case 'product.created':
            Product::updateOrCreate(
                ['stripe_product_id' => $data->id],
                [
                    'image_url' => $data->images[0] ?? null,
                    'title' => $data->name,
                    'description' => $data->description,
                    'published_date' => null,
                    'price' => $price,
                    'status' => 'draft',
                    'inventory' => 0,
                    'is_digital' => false,
                ],
            );
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


require __DIR__.'/auth.php';
