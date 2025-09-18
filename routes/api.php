<?php

use App\Http\Controllers\API\V1\BalanceController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Business\addBankAccountController;
use App\Http\Controllers\Personal\addBankAccountController as PersonaladdBankAccountController;
use App\Http\Controllers\Business\SubAccountController;
use App\Http\Controllers\Personal\SubAccountController as PersonalSubAccountController;
use App\Http\Controllers\Business\InvoicesController;
use App\Http\Controllers\Business\refundsController;
use App\Http\Controllers\Personal\refundsController as PersonalrefundsController;
use App\Http\Controllers\Personal\paymentsController;
use App\Http\Controllers\Personal\donationsController;

use App\Http\Controllers\Business\RemitaController;
use App\Http\Controllers\Business\TransactionHistoryController;
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
use App\Http\Controllers\Personal\AddMoneyController;
use App\Http\Controllers\Personal\BillPaymentController as PersonalBillPaymentController;
use App\Http\Controllers\Personal\CreateBankController as PersonalCreateBankController;
use App\Http\Controllers\Personal\NotificationController;
use App\Http\Controllers\Personal\OrganizationController;
use App\Http\Controllers\Personal\SendMoneyController as PersonalSendMoneyController;
use App\Http\Controllers\Personal\TransactionHistoryController as PersonalTransactionHistoryController;
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
Route::post('/auth/request-password-otp', [ForgetPasswordController::class, 'requestForgetPasswordOtp']);


// Route::get('/add_account', [CreateBankController::class, 'create'])->name('add_account.create');











Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/users', [RegisterController::class, 'getAllUsers']);
    Route::get('/getLoggedInUser', [RegisterController::class, 'getLoggedInUser']);
    Route::delete('/deleteUser/{email}', [RegisterController::class, 'deleteUser']);
    
    // api routes for payout acccount details 
    Route::post('business/bank-account', [addBankAccountController::class, 'store']);
    Route::post('business/bank-accounts/{id}/set-default', [addBankAccountController::class, 'setDefault']);
    Route::post('business/validate-payout-account-name', [addBankAccountController::class, 'validatePayoutAccountName']);
    Route::delete('business/delete-account/{id}', [addBankAccountController::class, 'destroy']);
    Route::delete('business/delete-accounts', [addBankAccountController::class, 'destroyAll']);
    Route::put('business/bankAccount/{id}', [addBankAccountController::class, 'update']);
    Route::get('business/bank-account/{id}', [addBankAccountController::class, 'edit']);
    Route::get('business/fetchBanks', [addBankAccountController::class, 'fetchlocalBanks']);
    Route::get('business/show-bank-accounts', [addBankAccountController::class, 'payouts']);
    
    // api routes for subaccounts details
    Route::post('business/subaccounts', [SubAccountController::class, 'store']);
    Route::get('business/show-subaccounts', [SubAccountController::class, 'show']);
    Route::get('business/show-subaccount/{id}', [SubAccountController::class, 'edit']);
    Route::put('business/update-subaccount/{id}', [SubAccountController::class, 'update']);
    Route::delete('business/delete-subaccount/{id}', [SubAccountController::class, 'destroy']);
    Route::delete('business/delete-subaccounts', [SubAccountController::class, 'destroyAll']);
    Route::get('business/fetchBanks', [SubAccountController::class, 'fetchlocalBanks']);
    Route::post('business/validateSubaccountName', [SubAccountController::class, 'validatePayoutAccountName']);
    
    //invoices section
    Route::get('/invoices', [InvoicesController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/create', [InvoicesController::class, 'create'])->name('invoices.create');
    Route::post('/invoices', [InvoicesController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{id}', [InvoicesController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{id}/edit', [InvoicesController::class, 'edit'])->name('invoices.edit');
    Route::put('/invoices/{id}', [InvoicesController::class, 'update'])->name('invoices.update');
    Route::delete('/invoices/{id}', [InvoicesController::class, 'destroy'])->name('invoices.destroy');
    
    //refund
    Route::get('/business/refunds', [refundsController::class, 'index'])->name('refunds.index');
    Route::get('/business/refunds/{id}', [refundsController::class, 'fetchRefund']);
    Route::post('/business/refunds', [refundsController::class, 'store'])->name('refund.store');
    Route::post('/business/refunds/{id}/status', [refundsController::class, 'updateStatus']);
    
    //remita
    Route::get('business/remita', [RemitaController::class, 'index']);
    Route::get('business/remita/{id}/export', [RemitaController::class, 'exportUserRemita']);
    // Route::get('business/remita/create', [RemitaController::class, 'create']);
    // Route::get('business/remita/{id}/edit', [RemitaController::class, 'edit']);
    Route::put('business/remita/{id}/update', [RemitaController::class, 'update']);
    Route::post('business/remita/store', [RemitaController::class, 'store']);
    Route::delete('business/remita/{id}/destory', [RemitaController::class, 'destroy']);
    

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
    Route::get('/fetchBanks', [AddBeneficiariesController::class, 'fetchBanks']);
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

    //API FOR PERSONAL dd
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
    Route::post('/auth/request-forgetpassword-otp-personal', [ForgetPasswordController::class, 'requestForgetPasswordOtpPersonal']);

    Route::group(['middleware' => ['auth:personal-api']], function () {
        Route::prefix('personal')->group(function () {

        Route::get('/personal-beneficias', [PersonalAddBeneficiariesController::class, 'index']);
        Route::post('/personal-add-baneficia', [PersonalAddBeneficiariesController::class, 'store']);
        Route::put('/personal-beneficias/{id}', [PersonalAddBeneficiariesController::class, 'update'])->name('beneficias.update'); 
        Route::delete('/personal-beneficias/{id}', [PersonalAddBeneficiariesController::class, 'destroy']);
        Route::post('/personal-fetchBanks', [PersonalAddBeneficiariesController::class, 'fetchBankss']);
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

        //payouts 
        Route::post('/bank-account', [PersonaladdBankAccountController::class, 'store']);
        Route::post('/validate-payout-account-name', [PersonaladdBankAccountController::class, 'validatePayoutAccountName']);
        Route::get('/show-bank-accounts', [PersonaladdBankAccountController::class, 'payouts']);
        Route::get('/bank-account/{id}', [PersonaladdBankAccountController::class, 'edit']);
        Route::get('/fetchBanks', [PersonaladdBankAccountController::class, 'fetchlocalBanks']);
        Route::post('/payout/{id}/set-default', [PersonaladdBankAccountController::class, 'setDefault']);
        Route::delete('/delete-account/{id}', [PersonaladdBankAccountController::class, 'destroy']);
        Route::delete('/delete-accounts', [PersonaladdBankAccountController::class, 'destroyAll']);
        Route::put('/bankAccount/{id}', [PersonaladdBankAccountController::class, 'update']);

        //subaccount
        Route::get('/subaccount', [PersonalSubAccountController::class, 'subaccount']);
        Route::get('/show-subaccount/{id}', [PersonalSubAccountController::class, 'edit']);
        Route::get('/show-subaccounts', [PersonalSubAccountController::class, 'show']);
        Route::get('/fetchBanks', [PersonalSubAccountController::class, 'fetchlocalBanks']);
        Route::delete('/deleteAllSubaccounts', [PersonalSubAccountController::class, 'destroyAll']);
        Route::delete('/deleteSubaccount/{id}', [PersonalSubAccountController::class, 'destroy']);
        Route::post('/subaccounts', [PersonalSubAccountController::class, 'store']);
        Route::post('/validateSubaccountName', [PersonalSubAccountController::class, 'validatePayoutAccountName']);
        Route::put('/updateSubaccount/{id}', [PersonalSubAccountController::class, 'update']);

        //refund
        Route::get('/refunds', [PersonalrefundsController::class, 'index']);
        Route::get('/fetchRefund/{id}', [PersonalrefundsController::class, 'fetchRefund']);
        Route::post('/refunds', [PersonalrefundsController::class, 'store']);
        Route::post('/refunds/{id}/status', [PersonalrefundsController::class, 'updateStatus']);
        
        //payments
        Route::get('/payments', [paymentsController::class, 'index']);
        Route::get('/payments/show/{id}', [paymentsController::class, 'show']);
        Route::get('/payments/paymentrecords', [paymentsController::class, 'paymentrecords']);
        Route::get('/payments/export', [paymentsController::class, 'exportUserPayments']);
        Route::post('/payments/store', [paymentsController::class, 'store']);
        Route::delete('/payments/{id}/destroy', [paymentsController::class, 'destroy']);
        Route::put('/payments/update/{id}', [paymentsController::class, 'update']);
        
        //donations
        Route::get('/donations', [donationsController::class, 'index']);
        Route::get('/donations/show/{id}', [donationsController::class, 'show']);
        Route::get('/donations/donationrecords', [donationsController::class, 'donationrecords']);
        Route::post('/donations/store', [donationsController::class, 'store']);
        Route::delete('/donations/{id}/destory', [donationsController::class, 'destory']);
        Route::put('/donations/update/{id}', [donationsController::class, 'update']);
=======
    
        // api routes for Send Money detailsdeactivateAccount
        Route::get('/personal-exchange-rate', [PersonalSendMoneyController::class, 'getExchangeRate']);
        Route::post('/personal-send', [PersonalSendMoneyController::class, 'sendTransaction'])->name('transactions.send');


        // Update Profile
        Route::post('/personal-profile', [OrganizationController::class, 'updateProfile']);
        Route::post('/personal-email', [OrganizationController::class, 'updateEmail']);
        Route::post('/personal-deactivate-account', [OrganizationController::class, 'deactivateAccount']);


        Route::post('/personal-topup', [AddMoneyController::class, 'topupWithCard']);



        Route::get('/personal-transactions', [PersonalTransactionHistoryController::class, 'personalTransactions']);
        Route::get('/personal-transactions/{status}', [PersonalTransactionHistoryController::class, 'filterPersonalTransactions']);

        Route::get('/personal-notifications', [NotificationController::class, 'index']);
        Route::get('/personal-notifications/unread', [NotificationController::class, 'unread']);
        Route::put('/personal-notifications/{id}/read', [NotificationController::class, 'markAsRead']);







        // ...other personal routes
    });
});


// Route::prefix('v1')->group(function () {
    
//     Route::get('/balances', [BalanceController::class, 'index']);
//     Route::post('/balances', [BalanceController::class, 'store']);
//     Route::get('/balances/{id}', [BalanceController::class, 'show']);
//     Route::patch('/balances/{id}', [BalanceController::class, 'update']);
// });

