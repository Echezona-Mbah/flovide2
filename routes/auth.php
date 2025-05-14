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
use App\Http\Controllers\Business\AddBeneficiariesController;
use App\Http\Controllers\Business\AddCustomerController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth')->group(function () {
    // Route::get('/dashboard', [RegisteredUserController::class, 'show'])->name('dashboard');
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
    // customer
        // beneficias
    Route::get('/customer', [AddCustomerController::class, 'index'])->name('customer');
    Route::get('/add_beneficias', [AddBeneficiariesController::class, 'create'])->name('add_beneficias.create');
    Route::post('/add_beneficias', [AddBeneficiariesController::class, 'store'])->name('add_beneficias.store');
    Route::get('/beneficias/{id}/edit', [AddBeneficiariesController::class, 'edit'])->name('beneficias.edit'); 
    Route::put('/beneficias/{id}', [AddBeneficiariesController::class, 'update'])->name('beneficias.update');
    Route::delete('/beneficia/{id}', [AddBeneficiariesController::class, 'destroy'])->name('beneficia.destroy');



    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
