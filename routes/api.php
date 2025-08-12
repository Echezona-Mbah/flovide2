<?php

use App\Http\Controllers\API\V1\BalanceController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\business\addBankAccountController;
use App\Http\Controllers\business\SubAccountController;
use App\Http\Controllers\business\TransactionHistoryController;
use App\Http\Controllers\Business\AddBeneficiariesController;
use App\Http\Controllers\Business\AddCustomerController;
use App\Http\Controllers\Business\BillPaymentController;
use App\Http\Controllers\Business\ChargebackController;
use App\Http\Controllers\Business\ComplianceController;
use App\Http\Controllers\Business\CreateBankController;
use App\Http\Controllers\Business\SendMoneyController;
use App\Http\Controllers\Business\SubscriptionController;
use App\Http\Controllers\Business\VirtualAccountController;
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

// Route::get('/add_account', [CreateBankController::class, 'create'])->name('add_account.create');











Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/users', [RegisterController::class, 'getAllUsers']);
    Route::delete('/deleteUser/{email}', [RegisterController::class, 'deleteUser']);
    // api routes for bank acccount details 
    Route::post('business/bank-account', [addBankAccountController::class, 'store']);
    Route::get('business/show-bank-accounts', [addBankAccountController::class, 'payouts']);
    Route::delete('business/delete-account/{id}', [addBankAccountController::class, 'destroy']);
    Route::delete('business/delete-accounts', [addBankAccountController::class, 'destroyAll']);
    Route::put('business/bankAccount/{id}', [addBankAccountController::class, 'update']);
    // api routes for sub accounts details
    Route::post('business/subaccounts', [SubAccountController::class, 'store']);
    Route::get('business/show-subaccounts', [SubAccountController::class, 'show']);
    Route::put('business/update-subaccount/{id}', [SubAccountController::class, 'update']);
    Route::delete('business/delete-subaccount/{id}', [SubAccountController::class, 'destroy']);
    Route::delete('business/delete-subaccounts', [SubAccountController::class, 'destroyAll']);
    // api for transaction history
    Route::get('business/showTransactions', [TransactionHistoryController::class, 'showAllTransactions']);
    Route::get('business/userTransactions', [TransactionHistoryController::class, 'transaction']);
    Route::get('business/userTransactions/{id}', [TransactionHistoryController::class, 'UserTransaction']);
    Route::post('business/transactions', [TransactionHistoryController::class, 'storeTransaction']);
    // api routes for beneficias details
    Route::get('/beneficias', [AddBeneficiariesController::class, 'index']);
    Route::post('/add-baneficia', [AddBeneficiariesController::class, 'store']);
    Route::put('/beneficias/{id}', [AddBeneficiariesController::class, 'update'])->name('beneficias.update'); 
    Route::delete('beneficias/{id}', [AddBeneficiariesController::class, 'destroy'])->name('beneficias.destroy');
    Route::get('/fetchBanks', [AddBeneficiariesController::class, 'fetchBankss']);
    Route::post('/validate-account', [AddBeneficiariesController::class, 'validateRecipient']);
    Route::get('/fetchcountrylist', [AddBeneficiariesController::class, 'fetchcountrylist']);
    Route::get('beneficia/all', [AddBeneficiariesController::class, 'allBeneficia']);

    // api routes for Send Money details
    Route::get('/exchange-rate', [SendMoneyController::class, 'getExchangeRate']);
    Route::post('/send', [SendMoneyController::class, 'sendTransaction'])->name('transactions.send');
    // api routes for Balance details
    Route::get('balances', [CreateBankController::class, 'index']);
    Route::get('/singlebalances', [CreateBankController::class, 'create']);
    Route::post('/createBalance', [CreateBankController::class, 'createBalance'])->name('ohentpay.createBalance');
    Route::post('/update_balance', [CreateBankController::class, 'UpdateBalance'])->name('update.balance');
    Route::get('/total-balance', [CreateBankController::class, 'getUserTotalBalance']);

    // api routes for Customers details
    Route::get('/customers', [AddCustomerController::class, 'index']);
    Route::post('/add-customers', [AddCustomerController::class, 'store']);
    Route::put('customers/{id}', [AddCustomerController::class, 'update'])->name('customers.update');
    Route::delete('customers/{id}', [AddCustomerController::class, 'destroy'])->name('customers.destroy');
    // api routes for Subscriptions details
    Route::get('/subscriptions', [SubscriptionController::class, 'index']);
    Route::post('/add-subscriptions', [SubscriptionController::class, 'store']);
    Route::put('subscriptions/{id}', [SubscriptionController::class, 'update']);
    Route::delete('/subscriptions/{id}', [SubscriptionController::class, 'destroy']);
    // api routes for DSTV details
    Route::post('/Dstvvariations', [BillPaymentController::class, 'getVariations']);
    Route::post('/Dstvverify', [BillPaymentController::class, 'verify']);
    Route::post('/Dstvpay', [BillPaymentController::class, 'store']);
    Route::get('/Dstvhistory', [BillPaymentController::class, 'index']);

    // Elecricity
    Route::post('/electricity_verify', [BillPaymentController::class, 'verifyElectricity']);
    Route::post('/electricitypay', [BillPaymentController::class, 'storeELectricity']);

    Route::post('/date_variations', [BillPaymentController::class, 'getDateVariations']);
    Route::post('/dataypay', [BillPaymentController::class, 'storeData']);

        // Virtual Account
    Route::get('/virtualCard', [VirtualAccountController::class, 'index']);
    Route::post('/virtualCard', [VirtualAccountController::class, 'createVirtualAccount']);
    Route::get('/allvirtualcard', [VirtualAccountController::class, 'allvirtualcard']);
    Route::get('/virtualcard/{id}', [VirtualAccountController::class, 'showVirtualCard']);
    Route::delete('/virtualCard/{id}', [VirtualAccountController::class, 'destroy'])->name('virtualCard.destroy');

    // Compliance
    Route::post('/cac', [ComplianceController::class, 'handleCac']);
    Route::post('/valid_id', [ComplianceController::class, 'handleValidid']);
    Route::post('/tax', [ComplianceController::class, 'handleTin']);
    Route::post('/utilitybill', [ComplianceController::class, 'handleUtilitybill']);
    Route::post('/bvn', [ComplianceController::class, 'handleBvn']);

    // Chargeback
    Route::get('/chargeback', [ChargebackController::class, 'index']);
    Route::post('/chargeback/submitEvidence', [ChargeBackController::class, 'submitEvidence'])->name('chargeback.submitEvidence');






    
    // Route::get('/balances', [BalanceController::class, 'index']);
    // Route::post('/balances', [BalanceController::class, 'store']);
    // Route::get('/balances/{id}', [BalanceController::class, 'show']);
    // Route::patch('/balances/{id}', [BalanceController::class, 'update']);







});


// Route::prefix('v1')->group(function () {
    
//     Route::get('/balances', [BalanceController::class, 'index']);
//     Route::post('/balances', [BalanceController::class, 'store']);
//     Route::get('/balances/{id}', [BalanceController::class, 'show']);
//     Route::patch('/balances/{id}', [BalanceController::class, 'update']);
// });

