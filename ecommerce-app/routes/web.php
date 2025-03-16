<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Log;

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


/**
 * prefixでadminを固定
 * 
 */
Route::prefix('admin')->name('admin.')->group(function() {

    Route::get('dashboard', function () {
        $page = 'dashboard';
        // ダミーデータ
        $data = ['test'=> 'これはダミーです'];

        return view('admin.dashboard', compact('page', 'data'));
    })->name('dashboard');

    Route::prefix('dashboard')->group(function() {
        Route::get('products', function () {
            $page = 'products';
            // ダミーデータ
            $data = ['test'=> 'これはダミーです'];
            return view('admin.dashboard', compact('page', 'data'));
        })->name('dashboard.products');

        Route::get('orders', function () {
            $page = 'orders';
            // ダミーデータ
            $data = ['test'=> 'これはダミーです'];
            return view('admin.dashboard', compact('page', 'data'));
        })->name('dashboard.orders');

        Route::get('product/create', function () {
            $page = 'product/create';
            // ダミーデータ
            $data = ['test'=> 'これはダミーです'];
            return view('admin.dashboard', compact('page', 'data'));
        })->name('dashboard.product.create');
    });
});


require __DIR__.'/auth.php';
