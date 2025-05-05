<?php

use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\business\add_bank_accountController;
use App\Http\Controllers\business\SubAccountController;
use App\Http\Controllers\business\Transaction_history_Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/register', [RegisterController::class, 'register']);
Route::post('auth/verify-email/{email}', [RegisterController::class, 'verifyEmail']);
Route::post('auth/new-email-otp/{email}', [RegisterController::class, 'verifyEmailOtp']);
Route::post('auth/login', [LoginController::class, 'login']);
Route::get('/country', [RegisterController::class, 'getAllCountry']);
Route::post('/auth/forgot-password', [ForgetPasswordController::class, 'forgotPassword']);
Route::post('/auth/forget-verify-otp', [ForgetPasswordController::class, 'verifyOTP']);
Route::post('/auth/reset-password', [ForgetPasswordController::class, 'resetPassword']);





Route::group(['middleware'=> ['auth:sanctum']],function(){
    Route::get('/users', [RegisterController::class, 'getAllUsers']);
    Route::delete('/deleteUser/{email}', [RegisterController::class, 'deleteUser']);
    // api routes for bank acccount details 
    Route::post('auth/bank-account', [add_bank_accountController::class, 'store']);
    Route::get('auth/show-bank-accounts', [add_bank_accountController::class, 'show']);
    Route::delete('auth/delete-account/{id}', [add_bank_accountController::class, 'destroy']);
    Route::delete('auth/delete-accounts', [add_bank_accountController::class, 'destroyAll']);
    Route::put('auth/bank-account/{id}', [add_bank_accountController::class, 'update']);
    // api routes for sub accounts details
    Route::post('auth/subaccounts', [SubAccountController::class, 'store']);
    Route::get('auth/show-subaccounts', [SubAccountController::class, 'show']);
    Route::put('auth/update-subaccount/{id}', [SubAccountController::class, 'update']);
    Route::delete('auth/delete-subaccount/{id}', [SubAccountController::class, 'destroy']);
    Route::delete('auth/delete-subaccounts', [SubAccountController::class, 'destroyAll']);
    // api for transaction history 
    Route::get('auth/show-transactions', [Transaction_history_Controller::class, 'showalltransaction']);
    Route::post('auth/transactions', [Transaction_history_Controller::class, 'storetransaction']);





});

