<?php

use Illuminate\Foundation\Application;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LaundryHistoryController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminUserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

// Landing Page
Route::get('/', fn() => Inertia::render('LandingPage'))->name('landing');

// Guest Routes (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => Inertia::render('Login'))->name('login');
    Route::get('/register', fn() => Inertia::render('Register'))->name('register.page');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.process');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.process');
});

// User Login Required
Route::middleware(['auth'])->group(function () {

    Route::get('/landing', fn() => Inertia::render('LandingPage'))->name('landingpage');
    Route::get('/reservasi', fn() => Inertia::render('Reservasi'))->name('reservasi');
    Route::get('/order', fn() => Inertia::render('Order'));

    Route::get('/user-profile', function () {
        return Inertia::render('Profile', [
            'user' => Auth::user(),
        ]);
    })->middleware(['auth']);

    Route::get('/test', fn() => Inertia::render('test'))->name('test');
    Route::get('/laundryhistory', fn() => Inertia::render('LaundryHistory'))->name('laundryhistory');

    Route::get('/mylaundry', [ReservasiController::class, 'index'])->name('mylaundry');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/reservasi/store', [ReservasiController::class, 'store'])->name('reservasi.store');
    Route::post('/reservasi/{id}/cancel', [ReservasiController::class, 'cancel'])->name('reservasi.cancel');
});
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin', function () {
        return Inertia::render('admin/Dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/payments', function () {
        return Inertia::render('admin/Payments');
    })->name('admin.payments');

    Route::get('/admin/products', function () {
        return Inertia::render('admin/Products');
    })->name('admin.products');

    Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders');
    Route::get('/admin/orders/data', [AdminOrderController::class, 'getData'])->name('admin.orders.data');
    Route::put('/admin/orders/{id}', [AdminOrderController::class, 'update'])->name('admin.orders.update');
    Route::delete('/admin/orders/{id}', [AdminOrderController::class, 'destroy'])->name('admin.orders.destroy');

    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    // Product CRUD
    Route::resource('products', ProductController::class)->except(['create', 'edit']);
});

// Default Register Route
Route::get('/Register', [RegisteredUserController::class, 'create'])->name('Register');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';