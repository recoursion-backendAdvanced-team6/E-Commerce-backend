<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/admin/dashbord', function() {
    $menuItems = [
        ['label' => '商品追加', 'url' => '/admin/dashbord/product/create'],
        ['label' => '商品一覧', 'url' => '/admin/dashbord/product/list'],
        ['label' => '注文一覧', 'url' => '/admin/dashbord/orders'],
    ];

    return view('admin.dashboard', ['menuItems' => $menuItems]);
});



require __DIR__.'/auth.php';
