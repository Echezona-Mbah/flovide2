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

;
    // Route::get('/dashboard', [RegisteredUserController::class, 'show'])->name('dashboard');
    Route::get('/verifyemail', [RegisteredUserController::class, 'showverifyEmail'])->name('verifyemail');
    Route::post('/verifyemail/{email}', [RegisteredUserController::class, 'verifyEmail'])->name('verifyemail.submit');
    Route::post('/resend-otp/{email}', [RegisteredUserController::class, 'resendOtp'])->name('resend.otp');






    // Route::get('verify-email', EmailVerificationPromptController::class)
    //     ->name('verification.notice');

    // Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
    //     ->middleware(['signed', 'throttle:6,1'])
    //     ->name('verification.verify');

    // Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    //     ->middleware('throttle:6,1')
    //     ->name('verification.send');

    // Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
    //     ->name('password.confirm');

    // Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
