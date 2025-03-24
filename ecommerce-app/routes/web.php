<?php

use App\Http\Controllers\DashboardController;
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


Route::prefix('admin')->name('admin.')->group(function() {
    Route::get('dashboard', [DashboardController::class, 'home'])->name('dashboard');
    Route::get('dashboard/products', [DashboardController::class, 'products'])->name('dashboard.products');
    Route::get('dashboard/orders', [DashboardController::class, 'orders'])->name('dashboard.orders');
    Route::get('dashboard/{page?}/{subpage?}', [DashboardController::class, 'home'])->name('dashboard.page.subpage');
});


require __DIR__.'/auth.php';
