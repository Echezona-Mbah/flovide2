<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\business\addBankAccountController;
use App\Http\Controllers\Business\AddBeneficiariesController;
use App\Http\Controllers\Business\AddCustomerController;
use App\Http\Controllers\business\SubAccountController;
use App\Http\Controllers\business\TransactionHistoryController;
use App\Http\Controllers\business\InvoicesController;
use App\Http\Controllers\business\refundsController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\HtmlMinifier;
use App\Http\Middleware\SecurityHeaders;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register.saveStepData');

    Route::post('register', [RegisteredUserController::class, 'saveStepData']);



    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgotpassword', [ForgetPasswordController::class, 'create'])
        ->name('forgotpassword');

    Route::post('forgotpassword', [ForgetPasswordController::class, 'forgotPassword']);

    Route::get('forget-verify-otp', [ForgetPasswordController::class, 'createverifyOTP'])
    ->name('forget-verify-otp');

    Route::post('/forget-verify-otp', [ForgetPasswordController::class, 'verifyOTP']);
    

    Route::get('resetPassword', [ForgetPasswordController::class, 'createresetPassword'])
    ->name('resetPassword');

    Route::post('/resetPassword', [ForgetPasswordController::class, 'resetPassword']);
});

// HtmlMinifier::class
Route::middleware(['auth'])->group(function () {

    Route::get('/payout', [addBankAccountController::class, 'payouts'])->name('payouts');
    Route::post('/payout', [addBankAccountController::class, 'store'])->name('payout.store');
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
    
    //invoices section
    Route::get('/invoices', [InvoicesController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/create', [InvoicesController::class, 'create'])->name('invoices.create');
    Route::post('/invoices', [InvoicesController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{id}', [InvoicesController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{id}/edit', [InvoicesController::class, 'edit'])->name('invoices.edit');
    Route::put('/invoices/{id}', [InvoicesController::class, 'update'])->name('invoices.update');
    Route::delete('/invoices/{id}', [InvoicesController::class, 'destroy'])->name('invoices.destroy');

    //refund
    Route::get('/refunds', [refundsController::class, 'index'])->name('refunds.index');
    Route::post('/refunds', [refundsController::class, 'store'])->name('refund.store');
    Route::get('/refunds/{id}', [refundsController::class, 'show'])->name('refund.show');
    Route::get('/refunds/{id}/edit', [refundsController::class, 'edit'])->name('refund.edit');
    Route::put('/refunds/{id}', [refundsController::class, 'update'])->name('refund.update');
    Route::delete('/refunds/{id}', [refundsController::class, 'destroy'])->name('refund.destroy');



    Route::get('/verifyemail', [RegisteredUserController::class, 'showverifyEmail'])->name('verifyemail');
    Route::post('/verifyemail/{email}', [RegisteredUserController::class, 'verifyEmail'])->name('verifyemail.submit');
    Route::post('/resend-otp/{email}', [RegisteredUserController::class, 'resendOtp'])->name('resend.otp');

    // beneficias
    Route::get('/beneficias', [AddBeneficiariesController::class, 'index'])->name('beneficias');
    Route::get('/add_beneficias', [AddBeneficiariesController::class, 'create'])->name('add_beneficias.create');
    Route::post('/add_beneficias', [AddBeneficiariesController::class, 'store'])->name('add_beneficias.store');
    Route::get('/beneficias/{id}/edit', [AddBeneficiariesController::class, 'edit'])->name('beneficias.edit'); 
    Route::put('/beneficias/{id}', [AddBeneficiariesController::class, 'update'])->name('beneficias.update');
    Route::delete('/beneficia/{id}', [AddBeneficiariesController::class, 'destroy'])->name('beneficia.destroy');
    Route::get('/beneficiaries/search', [AddBeneficiariesController::class, 'search'])->name('beneficiaries.search');



    // customer
    Route::get('/customer', [AddCustomerController::class, 'index'])->name('customer');
    Route::get('/customers/{id}', [AddCustomerController::class, 'show']);
    Route::get('/add_customer', [AddCustomerController::class, 'create'])->name('add_customer.create');
    Route::post('/add_customer', [AddCustomerController::class, 'store'])->name('add_customer.store');
    Route::get('/customer/{id}/json', [AddCustomerController::class, 'json']);
    Route::get('/customer/{id}/edit', [AddCustomerController::class, 'edit'])->name('customer.edit'); 
    Route::put('/customer/{id}', [AddCustomerController::class, 'update'])->name('customer.update');
    Route::delete('/customer/{id}', [AddCustomerController::class, 'destroy'])->name('customer.destroy');
    Route::get('/customer/search', [AddCustomerController::class, 'search'])->name('customer.search');
    Route::get('/customer/export-csv', [AddCustomerController::class, 'exportCsv'])->name('customer.export.csv');





    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
