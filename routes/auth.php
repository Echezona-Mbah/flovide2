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
use App\Http\Controllers\Business\addBankAccountController;
use App\Http\Controllers\Business\AddBeneficiariesController;
use App\Http\Controllers\Business\AddCustomerController;
use App\Http\Controllers\Business\BillPaymentController;
use App\Http\Controllers\Business\ChargebackController;
use App\Http\Controllers\Business\ComplianceController;
use App\Http\Controllers\Business\SubAccountController;
use App\Http\Controllers\Business\SubscriptionController;
use App\Http\Controllers\Business\TransactionHistoryController;
use App\Http\Controllers\Business\InvoicesController;
use App\Http\Controllers\Business\refundsController;
use App\Http\Controllers\Business\RemitaController;
use App\Http\Controllers\Business\CreateBankController;
use App\Http\Controllers\Business\OrganizationController;
use App\Http\Controllers\Business\SendMoneyController;
use App\Http\Controllers\Business\VirtualAccountController;
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


    Route::get('/verifyemail', [RegisteredUserController::class, 'showverifyEmail'])->name('verifyemail');
    Route::post('/resend-otp/{email}', [RegisteredUserController::class, 'resendOtp'])->name('resend.otp');
    Route::post('/verifyemail/{email}', [RegisteredUserController::class, 'verifyEmail'])->name('verifyemail.submit');

    
    Route::get('/team/invite/{token}', [OrganizationController::class, 'showInviteForm'])->name('team.accept-invite');
    Route::post('/team/invite/{token}', [OrganizationController::class, 'completeInvite'])->name('team.invite.complete');


});

// HtmlMinifier::class
Route::middleware(['auth'])->group(function () {

    Route::get('/verify_bvn', [RegisteredUserController::class, 'bvn'])->name('verify_bvn');
    Route::post('/verify_bvn', [RegisteredUserController::class, 'verifyBVN'])->name('bvn.verify.submit');



    Route::get('/payout', [addBankAccountController::class, 'payouts'])->name('payouts');
    Route::post('/payout', [addBankAccountController::class, 'store'])->name('payout.store');
    Route::put('bank-account/{id}', [addBankAccountController::class, 'update'])->name('update');
    Route::get('bank-account/{id}', [addBankAccountController::class, 'edit'])->name('edit');
    Route::delete('delete-account/{id}', [addBankAccountController::class, 'destroy'])->name('destroy');
    Route::post('/bank-accounts/{id}/set-default', [addBankAccountController::class, 'setDefault'])->name('setDefault');
    Route::delete('bank-accounts/delete-all', [addBankAccountController::class, 'destroyAll'])->name('destroyAll');
    Route::get('/bank-accounts/fetch-banks', [addBankAccountController::class, 'fetchlocalBanks'])->name('fetch.localbanks');
    Route::post('/validate-payout-account-name', [addBankAccountController::class, 'validatePayoutAccountName'])->name('validatePayoutAccountName');

    //subaccount
    Route::get('/subaccount', [SubAccountController::class, 'subaccount'])->name('subaccount');
    Route::delete('delete-subaccounts/delete-all', [SubAccountController::class, 'destroyAll'])->name('destroyAll');
    Route::post('subaccounts', [SubAccountController::class, 'store'])->name('subaccounts.store');
    Route::get('edit-subaccount/{id}', [SubAccountController::class, 'edit'])->name('subaccountEdit');
    Route::delete('deleteSubaccount/{id}', [SubAccountController::class, 'destroy'])->name('destroy');
    Route::put('updateSubaccount/{id}', [SubAccountController::class, 'update'])->name('updateSubAccount');
    Route::get('/subaccounts/fetch-banks', [SubAccountController::class, 'fetchlocalBanks'])->name('subaccounts.fetch.localbanks');
    Route::post('/subaccounts/validate-payout-account-name', [SubAccountController::class, 'validatePayoutAccountName'])->name('subaccounts.validatePayoutAccountName');

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
    Route::post('/refunds/{id}/status', [refundsController::class, 'updateStatus']);
    
    //remita
    Route::get('/remita', [RemitaController::class, 'index'])->name('remita.index');
    Route::get('/remita/create', [RemitaController::class, 'create'])->name('remita.create');
    Route::get('/remita/{id}/edit', [RemitaController::class, 'edit'])->name('remita.edit');
    Route::post('/remita/update', [RemitaController::class, 'update'])->name('remita.update');
    Route::post('/remita/{id}/destory', [RemitaController::class, 'destory'])->name('remita.destory');
    Route::post('/remita/store', [RemitaController::class, 'store'])->name('remita.store');
    


    // beneficias
    Route::get('/beneficias', [AddBeneficiariesController::class, 'index'])->name('beneficias');
    Route::get('/add_beneficias', [AddBeneficiariesController::class, 'create'])->name('add_beneficias.create');
    Route::post('/add_beneficias', [AddBeneficiariesController::class, 'store'])->name('add_beneficias.store');
    Route::get('/beneficias/{id}/edit', [AddBeneficiariesController::class, 'edit'])->name('beneficias.edit'); 
    Route::put('/beneficias/{id}', [AddBeneficiariesController::class, 'update'])->name('beneficias.update');
    Route::delete('/beneficia/{id}', [AddBeneficiariesController::class, 'destroy'])->name('beneficia.destroy');
    Route::get('/beneficiaries/search', [AddBeneficiariesController::class, 'search'])->name('beneficiaries.search');
    Route::get('/fetch-banks', [AddBeneficiariesController::class, 'fetchBanks'])->name('fetch.banks');
    Route::post('/validate-account', [AddBeneficiariesController::class, 'validateRecipient']);
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
    //subscriptions
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions');
    Route::get('/add_subscription', [SubscriptionController::class, 'create'])->name('add_subscription.create');
    Route::post('/add_subscription', [SubscriptionController::class, 'store'])->name('add_subscription.store');
    Route::get('/subscriptions/{id}/edit', [SubscriptionController::class, 'edit'])->name('subscriptions.edit');
    Route::put('/subscriptions/{id}', [SubscriptionController::class, 'update'])->name('subscriptions.update');
    /// Bill Payment
    Route::get('/bill_payment', [BillPaymentController::class, 'index'])->name('bill_payment');
    Route::post('/bill_payment', [BillPaymentController::class, 'store'])->name('billpayments.store');
    Route::post('/Dstvverify', [BillPaymentController::class, 'verify'])->name('Dstvverify');
    Route::post('/Dstvvariations', [BillPaymentController::class, 'getVariations']);
    Route::get('/Dstvhistory', [BillPaymentController::class, 'index']);

    Route::post('/verify-electricity', [BillPaymentController::class, 'verifyElectricity'])->name('verify.electricity');
    Route::get('/get-data-variations', [BillPaymentController::class, 'getDateVariations'])->name('get.data.variations');


   // create bank
    Route::get('/add_account', [CreateBankController::class, 'create'])->name('add_account.create');
    Route::post('/ohentpay/createBalance', [CreateBankController::class, 'createBalance'])->name('ohentpay.createBalance');
    Route::get('/ohentpay/balances', [CreateBankController::class, 'getBalances']);
    Route::post('/update-balance', [CreateBankController::class, 'UpdateBalance'])->name('update.balance');
    Route::post('/update-main-balance', [CreateBankController::class, 'updateMainBalance'])->name('main.balance.update');


    // Send
    Route::get('/send', [SendMoneyController::class, 'index'])->name('send');
    Route::get('/exchange-rate', [SendMoneyController::class, 'getExchangeRate']);
    Route::post('/send', [SendMoneyController::class, 'sendTransaction'])->name('send');

    // Virtual Account
    Route::get('/virtualCard', [VirtualAccountController::class, 'index'])->name('virtualCard');
    Route::post('/virtualCard', [VirtualAccountController::class, 'createVirtualAccount'])->name('virtualCard.store');
    Route::get('/allvirtualcard', [VirtualAccountController::class, 'allvirtualcard'])->name('allvirtualcard');
    Route::delete('/virtualCard/{id}', [VirtualAccountController::class, 'destroy'])->name('virtualCard.destroy');


    // Chargeback
    Route::get('/chargeback', [ChargebackController::class, 'index'])->name('chargeback');
    Route::post('/chargeback/submitEvidence', [ChargeBackController::class, 'submitEvidence'])->name('chargeback.submitEvidence');


    //organization
    Route::get('/organization', [OrganizationController::class, 'index'])->name('organization');
    Route::post('/organization', [OrganizationController::class, 'store'])->name('team.store');
    Route::patch('/organization/{id}', [OrganizationController::class, 'updateRole'])->name('members.updateRole');

    Route::get('/organization_setting', [OrganizationController::class, 'indexsetting'])->name('organization_setting');
    Route::get('/organization_plan', [OrganizationController::class, 'indexplan'])->name('organization_plan');

    Route::get('/compliance', [ComplianceController::class, 'index'])->name('compliance');
    Route::post('/compliance', [ComplianceController::class, 'store'])->name('compliance.store');




  

















    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
