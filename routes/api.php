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
use App\Http\Controllers\Personal\AddBeneficiariesController as PersonalAddBeneficiariesController;
use App\Http\Controllers\Personal\BillPaymentController as PersonalBillPaymentController;
use App\Http\Controllers\Personal\CreateBankController as PersonalCreateBankController;
use App\Http\Controllers\Personal\VirtualAccountController as PersonalVirtualAccountController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/register', [RegisterController::class, 'registerUser']);
Route::post('auth/verify-email', [RegisterController::class, 'verifyEmail']);
Route::post('auth/new-email-otp', [RegisterController::class, 'verifyEmailOtp']);
Route::post('auth/login', [LoginController::class, 'loginUser']);
Route::get('/country', [RegisterController::class, 'getAllCountry']);
Route::post('/auth/forgot-password', [ForgetPasswordController::class, 'forgotPassword']);
Route::post('/auth/forget-verify-otp', [ForgetPasswordController::class, 'verifyOTP']);
Route::post('/auth/reset-password', [ForgetPasswordController::class, 'resetPasswordapi']);


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/users', [RegisterController::class, 'getAllUsers']);
    Route::get('/getLoggedInUser', [RegisterController::class, 'getLoggedInUser']);
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
    Route::post('/Dstvpay', [BillPaymentController::class, 'handleDstv']);
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


    // Route::get('/personal', [RegisterController::class, 'getAllPersonal']);
    // Route::get('/getLoggedInPersonal', [RegisterController::class, 'getLoggedInPersonal']);
    // Route::delete('/deletePersonal/{email}', [RegisterController::class, 'deletePersonal']);

    //API FOR PERSONAL

Route::middleware('auth:personal-api')->get('/beneficiaries', function (Request $request) {
    return $request->user(); 
});
// for personal
Route::post('auth/registerpersonal', [RegisterController::class, 'registerPersonal']);
Route::post('auth/verify-personalemail', [RegisterController::class, 'verifyPersonalEmail']);
Route::post('auth/new-personalemail-otp', [RegisterController::class, 'verifyPersonalEmailOtp']);
Route::post('auth/login-personal', [LoginController::class, 'loginPersonal']);
Route::post('/auth/forgot-password-personal', [ForgetPasswordController::class, 'forgotPasswordPersonal']);
Route::post('/auth/forget-verify-otp-personal', [ForgetPasswordController::class, 'verifyOTPPersonal']);
Route::post('/auth/reset-password-personal', [ForgetPasswordController::class, 'resetPasswordapiPersonal']);

Route::group(['middleware' => ['auth:personal-api']], function () {
    Route::prefix('personal')->group(function () {

    Route::get('/personal-beneficias', [PersonalAddBeneficiariesController::class, 'index']);
    Route::post('/personal-add-baneficia', [PersonalAddBeneficiariesController::class, 'store']);
    Route::put('/personal-beneficias/{id}', [PersonalAddBeneficiariesController::class, 'update'])->name('beneficias.update'); 
    Route::delete('personal-beneficias/{id}', [AddBeneficiariesController::class, 'destroy'])->name('beneficias.destroy');
    Route::get('/personal-fetchBanks', [PersonalAddBeneficiariesController::class, 'fetchBankss']);
    Route::post('/personal-validate-account', [PersonalAddBeneficiariesController::class, 'validateRecipient']);
    Route::get('/personal-fetchcountrylist', [PersonalAddBeneficiariesController::class, 'fetchcountrylist']);
    Route::get('personal-beneficia/all', [PersonalAddBeneficiariesController::class, 'allBeneficia']);


        // Virtual Account
    Route::get('/personal-virtualCard', [PersonalVirtualAccountController::class, 'index']);
    Route::post('personal-virtualCard', [PersonalVirtualAccountController::class, 'createVirtualAccount']);
    Route::get('/personal-allvirtualcard', [PersonalVirtualAccountController::class, 'allvirtualcard']);
    Route::get('/personal-virtualcard/{id}', [PersonalVirtualAccountController::class, 'showVirtualCard']);
    Route::delete('/personal-virtualCard/{id}', [PersonalVirtualAccountController::class, 'destroy'])->name('virtualCard.destroy');


        // api routes for DSTV details
    Route::get('/personal-Dstvvariations', [PersonalBillPaymentController::class, 'getVariations']);
    Route::post('/personal-Dstvverify', [PersonalBillPaymentController::class, 'verify']);
    Route::post('/personal-Dstvpay', [PersonalBillPaymentController::class, 'handleDstv']);
    Route::get('/personal-billhistory', [PersonalBillPaymentController::class, 'getUserBillPayments']);

    // Elecricity
    Route::get('/personal-electricityvariations', [PersonalBillPaymentController::class, 'getElectricityVariations']);
    Route::post('/personal-electricity_verify', [PersonalBillPaymentController::class, 'verifyElectricity']);
    Route::post('/personal-electricitypay', [PersonalBillPaymentController::class, 'handleElectricity']);
    // DATA
    Route::get('/personal-service_id', [PersonalBillPaymentController::class, 'getDataServiceId']);
    Route::post('/personal-date_variations', [PersonalBillPaymentController::class, 'getDateVariations']);
    Route::post('/personal-dataypay', [PersonalBillPaymentController::class, 'handleData']);

        // api routes for Balance details
    Route::get('/personal-balances', [PersonalCreateBankController::class, 'index']);
    Route::get('/personal-singlebalances', [PersonalCreateBankController::class, 'create']);
    Route::post('/personal-createBalance', [PersonalCreateBankController::class, 'createBalance'])->name('ohentpay.createBalance');
    Route::post('/personal-update_balance', [PersonalCreateBankController::class, 'UpdateBalance'])->name('update.balance');
    Route::get('/personal-total-balance', [PersonalCreateBankController::class, 'getUserTotalBalance']);



        // ...other personal routes
    });
});


// Route::prefix('v1')->group(function () {
    
//     Route::get('/balances', [BalanceController::class, 'index']);
//     Route::post('/balances', [BalanceController::class, 'store']);
//     Route::get('/balances/{id}', [BalanceController::class, 'show']);
//     Route::patch('/balances/{id}', [BalanceController::class, 'update']);
// });

