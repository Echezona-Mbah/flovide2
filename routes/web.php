<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BusinessController;
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

Route::middleware(['auth', 'verified'])->prefix('business')->name('business.')->group(function () {
    Route::get('/payout', [BusinessController::class, 'payouts'])->name('payouts');
    Route::get('/subaccount', [BusinessController::class, 'subaccount'])->name('subaccount');
});

require __DIR__ . '/auth.php';
