<?php

use Illuminate\Foundation\Application;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LaundryHistoryController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


// Landing Page
Route::get('/', fn() => Inertia::render('LandingPage', [
    'products' => \App\Models\Product::all()
]))->name('landing');

// Guest Routes (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => Inertia::render('Login'))->name('login');
    Route::get('/register', fn() => Inertia::render('Register'))->name('register.page');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.process');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.process');

    // Halaman lupa password
    Route::get('/forgot-password', function () {
        return Inertia::render('ForgotPassword');
    })->name('password.request');

    // Kirim link reset password
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    // Halaman reset password (via link email)
    Route::get('/reset-password/{token}', function ($token) {
        return Inertia::render('ResetPassword', [
            'token' => $token,
            'email' => request('email')
        ]);
    })->name('password.reset');

    // Simpan password baru
    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');        
});

// User Login Required
Route::middleware(['auth'])->group(function () {
    Route::post('/payment/upload/{id}', [PaymentController::class, 'uploadProof'])
        ->name('payment.upload');

    Route::get('/landing', fn() => Inertia::render('LandingPage', [
        'products' => \App\Models\Product::all()
    ]))->name('landingpage');
    Route::get('/reservasi', [ReservasiController::class, 'create'])->name('reservasi');
    Route::get('/order', [UserOrderController::class, 'index'])->name('order');

    Route::get('/user-profile', [UserProfileController::class, 'index'])->name('profile');

    Route::get('/test', fn() => Inertia::render('test'))->name('test');
    Route::get('/laundryhistory', [LaundryHistoryController::class, 'index'])->name('laundryhistory');

    Route::get('/mylaundry', [ReservasiController::class, 'index'])->name('mylaundry');
    Route::post('/profile/update', [UserProfileController::class, 'update'])->name('user-profile.update');
    Route::post('/reservasi/store', [ReservasiController::class, 'store'])->name('reservasi.store');
    Route::post('/reservasi/{id}/cancel', [ReservasiController::class, 'cancel'])->name('reservasi.cancel');
});
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin', [App\Http\Controllers\AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/payments', [App\Http\Controllers\AdminPaymentController::class, 'index'])->name('admin.payments');
    Route::post('/admin/payments/{id}/verify', [App\Http\Controllers\AdminPaymentController::class, 'verify'])->name('admin.payments.verify');
    Route::post('/admin/payments/{id}/reject', [App\Http\Controllers\AdminPaymentController::class, 'reject'])->name('admin.payments.reject');

    Route::get('/admin/products', [ProductController::class, 'adminIndex'])->name('admin.products');

    Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders');
    Route::get('/admin/orders/data', [AdminOrderController::class, 'getData'])->name('admin.orders.data');
    Route::put('/admin/orders/{id}', [AdminOrderController::class, 'update'])->name('admin.orders.update');
    Route::delete('/admin/orders/{id}', [AdminOrderController::class, 'destroy'])->name('admin.orders.destroy');

    // Users
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    // Product CRUD
    Route::resource('products', ProductController::class)->except(['create', 'edit']);
});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';