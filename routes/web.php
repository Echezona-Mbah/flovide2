<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\business\addBankAccountController;
use App\Http\Controllers\business\SubAccountController;
use App\Http\Controllers\business\TransactionHistoryController;
use App\Http\Controllers\business\InvoicesController;
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
    Route::get('/payout', [addBankAccountController::class, 'payouts'])->name('payouts');
    Route::post('/payout', [addBankAccountController::class, 'store'])->name('store');
    Route::put('bank-account/{id}', [addBankAccountController::class, 'update'])->name('update');
    Route::get('bank-account/{id}', [addBankAccountController::class, 'edit'])->name('edit');
    Route::delete('delete-account/{id}', [addBankAccountController::class, 'destroy'])->name('destroy');
    Route::post('/bank-accounts/{id}/set-default', [addBankAccountController::class, 'setDefault'])->name('setDefault');
    Route::delete('bank-accounts/delete-all', [addBankAccountController::class, 'destroyAll'])->name('destroyAll');

    //subaccount
    Route::get('/subaccount', [SubAccountController::class, 'subaccount'])->name('subaccount');
    Route::delete('delete-subaccounts/delete-all', [SubAccountController::class, 'destroyAll'])->name('destroyAll');
    Route::post('subaccounts', [SubAccountController::class, 'store'])->name('store');
    Route::get('edit-subaccount/{id}', [SubAccountController::class, 'edit'])->name('subaccountEdit');
    Route::delete('deleteSubaccount/{id}', [SubAccountController::class, 'destroy'])->name('destroy');
    Route::put('updateSubaccount/{id}', [SubAccountController::class, 'update'])->name('updateSubAccount');
    Route::post('/bankSubAccounts/{id}/set-default', [SubAccountController::class, 'setDefault'])->name('setDefault');

    //transaction history
    Route::get('/transactionHistory', [TransactionHistoryController::class, 'transaction'])->name('transactionHistory');


});

require __DIR__ . '/auth.php';
