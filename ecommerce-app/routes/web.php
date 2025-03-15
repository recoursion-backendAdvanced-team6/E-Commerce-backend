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

    return view('admin.dashboard');
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
