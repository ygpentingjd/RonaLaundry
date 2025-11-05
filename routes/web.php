<?php

use Illuminate\Foundation\Application;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

Route::get('/', fn() => Inertia::render('LandingPage'))->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => Inertia::render('Login'))->name('login');
    Route::get('/register', fn() => Inertia::render('Register'))->name('register.page');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.process');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.process');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/landing', fn() => Inertia::render('LandingPage'))->name('landingpage');
    Route::get('/reservasi', fn() => Inertia::render('Reservasi'));
    Route::get('/order', fn() => Inertia::render('Order'));
    Route::get('/user-profile', function () {
    return Inertia::render('Profile', [
        'user' => Auth::user(),
    ]);
    })->middleware(['auth']);
    Route::get('/admin-dashboard', fn() => Inertia::render('AdminDashboard'));
    Route::get('/test', fn() => Inertia::render('test'))->name('test');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/Register', [RegisteredUserController::class, 'create'])
    ->name('Register');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';