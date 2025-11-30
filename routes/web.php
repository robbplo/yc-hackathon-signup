<?php

use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/subscription/checkout', [SubscriptionController::class, 'showCheckout'])->name('subscription.checkout');
    Route::post('/subscription/checkout', [SubscriptionController::class, 'checkout'])->name('subscription.create');
    Route::get('/subscription/return', [SubscriptionController::class, 'afterPayment'])->name('subscription.return');
});

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
