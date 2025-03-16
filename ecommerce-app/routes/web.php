<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;

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
    Route::get('/dashbord/{page?}', function ($page='products') {
        return view('admin.dashboard', compact('page'));
    })->name('dashboard');
});

Route::get('/admin/dashbord/product/create', function() {
    return view();
});

Route::get('/admin/dashbord/product/list', function() {
    return view();
});

Route::get('/admin/dashbord/product/orders', function() {
    return view();
});

require __DIR__.'/auth.php';
