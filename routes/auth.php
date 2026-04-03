<?php

use App\Http\Controllers\Admin\AddAdminController;
use App\Http\Controllers\Admin\AllAccountController;
use App\Http\Controllers\Admin\AllBeneficiasController;
use App\Http\Controllers\Admin\AllBillPaymentController;
use App\Http\Controllers\Admin\AllChargebackController;
use App\Http\Controllers\Admin\AllCustomersController;
use App\Http\Controllers\Admin\AllDonationController;
use App\Http\Controllers\Admin\AllInvoiceController;
use App\Http\Controllers\Admin\AllPaymentController;
use App\Http\Controllers\Admin\AllRefundController;
use App\Http\Controllers\Admin\AllRemitaController;
use App\Http\Controllers\Admin\AllSubaccountController;
use App\Http\Controllers\Admin\AllSubscriptionController;
use App\Http\Controllers\Admin\AllTeamMembersController;
use App\Http\Controllers\Admin\BusinessAccountController;
use App\Http\Controllers\Admin\CareerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PersonalAccountController;
use App\Http\Controllers\Admin\RegisterController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TransactionHistoryController as AdminTransactionHistoryController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Business\addBankAccountController;
use App\Http\Controllers\Business\AddBeneficiariesController;
use App\Http\Controllers\Business\AddCustomerController;
use App\Http\Controllers\Business\AddMoneyController;
use App\Http\Controllers\Business\BillPaymentController;
use App\Http\Controllers\Business\ChargebackController;
use App\Http\Controllers\Business\ComplianceController;
use App\Http\Controllers\Business\SubAccountController;
use App\Http\Controllers\Business\SubscriptionController;
use App\Http\Controllers\Business\TransactionHistoryController;
use App\Http\Controllers\Business\InvoicesController;
use App\Http\Controllers\Business\refundsController;
use App\Http\Controllers\Business\RemitaController;
use App\Http\Controllers\Business\PaymentController;
use App\Http\Controllers\Business\DonationController;
use App\Http\Controllers\Business\CreateBankController;
use App\Http\Controllers\Business\NotificationController;
use App\Http\Controllers\Business\OrganizationController;
use App\Http\Controllers\Business\SendMoneyController;
use App\Http\Controllers\Business\VirtualAccountController;
use App\Http\Controllers\Business\WebhookController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Business\DashboardController as BusinessDashboardController;
use App\Http\Controllers\Business\referralLinkController;
use App\Http\Controllers\Ibanq\IbanqBeneficiaryAccountController;
use App\Http\Controllers\Ibanq\IbanqBeneficiaryController;
use App\Http\Controllers\Orchard\OrchardController;
use App\Http\Controllers\Payaza\PayoutController;
use App\Http\Controllers\Pivot\PivotController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\HtmlMinifier;
use App\Http\Middleware\SecurityHeaders;

// Route::get('/test-auth', function () {
//     return auth()->check() ? 'Logged in' : 'Guest';
// });

Route::get('/force-logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return 'Logged out';
});


Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register.saveStepData');

    Route::post('register', [RegisteredUserController::class, 'saveStepData']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgotpassword', [ForgetPasswordController::class, 'create'])->name('forgotpassword');

    Route::post('forgotpassword', [ForgetPasswordController::class, 'forgotPassword']);

    Route::get('forget-verify-otp', [ForgetPasswordController::class, 'createverifyOTP'])->name('forget-verify-otp');

    Route::post('/forget-verify-otp', [ForgetPasswordController::class, 'verifyOTP']);

    Route::get('resetPassword', [ForgetPasswordController::class, 'createresetPassword'])->name('resetPassword');

    Route::post('/resetPassword', [ForgetPasswordController::class, 'resetPassword']);


    Route::get('/verifyemail', [RegisteredUserController::class, 'showverifyEmail'])->name('verifyemail');
    Route::post('/resend-otp/{email}', [RegisteredUserController::class, 'resendOtp'])->name('resend.otp');
    Route::post('/verifyemail/{email}', [RegisteredUserController::class, 'verifyEmail'])->name('verifyemail.submit');

    
    Route::get('/team/invite/{token}', [OrganizationController::class, 'showInviteForm'])->name('team.accept-invite');
    Route::post('/team/invite/{token}', [OrganizationController::class, 'completeInvite'])->name('team.invite.complete');

    Route::get('/otp', [OtpController::class, 'index'])->name('otp.form');
    Route::post('/otp', [OtpController::class, 'verify'])->name('otp.verify');
    Route::post('/otp/resend', [OtpController::class, 'resend'])->name('otp.resend');

});

//payment checkout
Route::get('/payment/paymentcheckout/{id}', [PaymentController::class, 'paymentcheckout'])->name('payment.checkout');
Route::post('/payment/paymentpay', [PaymentController::class, 'paymentpay'])->name('payment.pay');

//donation checkout
Route::get('/donation/donationcheckout/{id}', [DonationController::class, 'donationcheckout'])->name('donation.checkout');
Route::post('/donation/donationpay', [DonationController::class, 'donationpay'])->name('donation.pay');

//remita checkout
Route::get('/remita/remitacheckout/{id}', [RemitaController::class, 'remitacheckout'])->name('remita.checkout');
Route::post('/remita/remitapay', [RemitaController::class, 'remitapay'])->name('remita.pay');

//invoice receipt link
Route::get('/invoices/receipts/{tracking_code}', [InvoicesController::class, 'showReceipt'])->name('invoices.receipt');

//Subscription checkout
Route::get('/subscription/subscriptioncheckout/{id}', [SubscriptionController::class, 'subscriptioncheckout'])->name('subscription.checkout');
Route::post('/subscription/subscriptionpay', [PaymentController::class, 'paymentpay'])->name('payment.pay');

//referral page route
Route::get('/referral', [referralLinkController::class, 'index'])->name('referral');



Route::post('/pivot/account-validation', [PivotController::class, 'accountValidation'])->name('pivot.account.validation');

Route::get('/banks/filter', [AddBeneficiariesController::class,'banks'])->name('banks.filter');
Route::post('/payaza/account-enquiry', [PayoutController::class, 'accountEnquiry'])->name('payaza.account-enquiry');

Route::post('/appmobile/account-inquiry', [OrchardController::class, 'appMobileAccountInquiry'])->name('appmobile.account-enquiry');




// HtmlMinifier::class
Route::middleware(['auth','business.verified'])->group(function () {

        Route::get('/dashboard/exchange-rate', [BusinessDashboardController::class, 'getExchangeRates']);


    Route::get('/verify_bvn', [RegisteredUserController::class, 'bvn'])->name('verify_bvn');
    Route::post('/verify_bvn', [RegisteredUserController::class, 'verifyBVN'])->name('bvn.verify.submit');

    //transaction history
    Route::get('/transactionHistory', [TransactionHistoryController::class, 'transaction'])->name('transactionHistory');

    //refund
    Route::get('/refunds', [refundsController::class, 'index'])->name('refunds.index');
    Route::post('/refunds', [refundsController::class, 'store'])->name('refund.store');
    Route::post('/refunds/{id}/status', [refundsController::class, 'updateStatus']);

      //webhook
    // Route::get('/webhook', [WebhookController::class, 'index'])->name('webhook');
    
    //top-up your wallet
    Route::get('/top-up', function () {
        return view('business.top_up_wallet');
    })->name('top-up');

    Route::get('/top-up-review', function () {
        return view('business.top_up_review');
    })->name('top-up-review');


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
    Route::get('/exchangesend', [SendMoneyController::class, 'indexexc'])->name('exchangesend');



    
    //organization
    Route::get('/organization', [OrganizationController::class, 'index'])->name('organization');
    Route::post('/organization', [OrganizationController::class, 'store'])->name('team.store');
    Route::patch('/organization/{id}', [OrganizationController::class, 'updateRole'])->name('members.updateRole');


    Route::get('/organization_setting', [OrganizationController::class, 'indexsetting'])->name('organization_setting');
    Route::post('/organization_setting', [OrganizationController::class, 'storesetting'])->name('organization_setting.store');

    // Route::post('/update-password', [OrganizationController::class, 'storesetting'])->name('password.update');
    Route::get('/organization_plan', [OrganizationController::class, 'indexplan'])->name('organization_plan');


    Route::get('/compliance', [ComplianceController::class, 'index'])->name('compliance');
    Route::post('/compliance', [ComplianceController::class, 'store'])->name('compliance.store');
    Route::get('/sumsub/webhook', [ComplianceController::class, 'handle'])->name('sumsub.webhook');

    
    Route::get('/add_money', [AddMoneyController::class, 'index'])->name('add_money');


    Route::get('/notifications', [NotificationController::class, 'index']);


    Route::get('/webhook', [WebhookController::class, 'index'])->name('business.webhooks');
    Route::post('/webhook', [WebhookController::class, 'update'])->name('business.webhooks.update');
    Route::post('/webhook/regenerate-secret', [WebhookController::class, 'regenerateSecret'])
        ->name('business.webhooks.regenerate-secret');



    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


    //payout account
    // Route::get('/payout', [addBankAccountController::class, 'payouts'])->name('payouts');
    // Route::get('bank-account/{id}', [addBankAccountController::class, 'edit'])->name('edit');
    // Route::get('/bank-accounts/fetch-banks', [addBankAccountController::class, 'fetchlocalBanks'])->name('fetch.localbanks');
    // Route::put('bank-account/{id}', [addBankAccountController::class, 'update'])->name('update');
    // Route::delete('delete-account/{id}', [addBankAccountController::class, 'destroy'])->name('destroy');
    // Route::delete('bank-accounts/delete-all', [addBankAccountController::class, 'destroyAll'])->name('destroyAll');
    // Route::post('/payout', [addBankAccountController::class, 'store'])->name('payout.store');
    // Route::post('/bank-accounts/{id}/set-default', [addBankAccountController::class, 'setDefault'])->name('setDefault');
    // Route::post('/validate-payout-account-name', [addBankAccountController::class, 'validatePayoutAccountName'])->name('validatePayoutAccountName');

    //subaccount
    // Route::get('/subaccount', [SubAccountController::class, 'subaccount'])->name('subaccount');
    // Route::delete('delete-subaccounts/delete-all', [SubAccountController::class, 'destroyAll'])->name('destroyAll');
    // Route::post('subaccounts', [SubAccountController::class, 'store'])->name('subaccounts.store');
    // Route::get('edit-subaccount/{id}', [SubAccountController::class, 'edit'])->name('subaccountEdit');
    // Route::delete('deleteSubaccount/{id}', [SubAccountController::class, 'destroy'])->name('destroy');
    // Route::put('updateSubaccount/{id}', [SubAccountController::class, 'update'])->name('updateSubAccount');
    // Route::get('/subaccounts/fetch-banks', [SubAccountController::class, 'fetchlocalBanks'])->name('subaccounts.fetch.localbanks');
    // Route::post('/subaccounts/validate-payout-account-name', [SubAccountController::class, 'validatePayoutAccountName'])->name('subaccounts.validatePayoutAccountName');


    
    //invoices section
    // Route::get('/invoices', [InvoicesController::class, 'index'])->name('invoices.index');
    // Route::get('/invoices/create', [InvoicesController::class, 'create'])->name('invoices.create');
    // Route::post('/invoices', [InvoicesController::class, 'store'])->name('invoices.store');
    // Route::get('/invoices/{id}', [InvoicesController::class, 'show'])->name('invoices.show');
    // Route::get('/invoices/{id}/edit', [InvoicesController::class, 'edit'])->name('invoices.edit');
    // Route::put('/invoices/{id}', [InvoicesController::class, 'update'])->name('invoices.update');
    // Route::delete('/invoices/{id}', [InvoicesController::class, 'destroy'])->name('invoices.destroy');


    
    //remita
    // Route::get('/remita', [RemitaController::class, 'index'])->name('remita.index');
    // Route::get('/remita/create', [RemitaController::class, 'create'])->name('remita.create');
    // Route::get('/remita/{id}/export', [RemitaController::class, 'exportUserRemita'])->name('remite.export');
    // Route::get('/remita/{id}/edit', [RemitaController::class, 'edit'])->name('remita.edit');
    // Route::put('/remita/{id}/update', [RemitaController::class, 'update'])->name('remita.update');
    // Route::post('/remita/store', [RemitaController::class, 'store'])->name('remita.store');
    // Route::delete('/remita/{id}/destory', [RemitaController::class, 'destroy']);
    
    //donations
    // Route::get('/donation', [DonationController::class, 'donationIndex'])->name('donation.index');
    // Route::get('/donation/create', [DonationController::class, 'donationCreate'])->name('donation.create');
    // Route::get('/donation/{id}/export', [DonationController::class, 'exportUserDonation'])->name('donation.export');
    // Route::get('/donation/edit/{id}', [DonationController::class, 'donationEdit'])->name('donation.edit');
    // Route::put('/donation/update/{id}', [DonationController::class, 'donationUpdate'])->name('donation.update');
    // Route::post('/donation/store', [DonationController::class, 'donationStore'])->name('donation.store');
    // Route::delete('/donation/{id}/destory', [DonationController::class, 'donationDestroy']);


    //payment
    // Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
    // Route::get('/payment/create', [PaymentController::class, 'create'])->name('payment.create');
    // Route::get('/payment/{id}/export', [PaymentController::class, 'exportUserPayments'])->name('payment.export');
    // Route::get('/payment/{id}/edit', [PaymentController::class, 'edit'])->name('payment.edit');
    // // Route::get('/payment/{id}/paymentcheckout', [PaymentController::class, 'paymentcheckout'])->name('payment.checkout');
    // Route::put('/payment/{id}/update', [PaymentController::class, 'update'])->name('payment.update');
    // Route::post('/payment/store', [PaymentController::class, 'store'])->name('payment.store');
    // Route::delete('/payment/{id}/destory', [PaymentController::class, 'destroy']);

  
    // customer
    // Route::get('/customer', [AddCustomerController::class, 'index'])->name('customer');
    // Route::get('/customers/{id}', [AddCustomerController::class, 'show']);
    // Route::get('/add_customer', [AddCustomerController::class, 'create'])->name('add_customer.create');
    // Route::post('/add_customer', [AddCustomerController::class, 'store'])->name('add_customer.store');
    // Route::get('/customer/{id}/json', [AddCustomerController::class, 'json']);
    // Route::get('/customer/{id}/edit', [AddCustomerController::class, 'edit'])->name('customer.edit'); 
    // Route::put('/customer/{id}', [AddCustomerController::class, 'update'])->name('customer.update');
    // Route::delete('/customer/{id}', [AddCustomerController::class, 'destroy'])->name('customer.destroy');
    // Route::get('/customer/search', [AddCustomerController::class, 'search'])->name('customer.search');
    // Route::get('/customer/export-csv', [AddCustomerController::class, 'exportCsv'])->name('customer.export.csv');
    // Route::get('/fetch-banks', [AddCustomerController::class, 'fetchBanks'])->name('fetch.banks');
    //subscriptions
    // Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions');
    // Route::get('/add_subscription', [SubscriptionController::class, 'create'])->name('add_subscription.create');
    // Route::post('/add_subscription', [SubscriptionController::class, 'store'])->name('add_subscription.store');
    // Route::get('/subscriptions/{id}/edit', [SubscriptionController::class, 'edit'])->name('subscriptions.edit');
    // Route::put('/subscriptions/{id}', [SubscriptionController::class, 'update'])->name('subscriptions.update');
    // Route::get('/subscriptions/{id}/export', [SubscriptionController::class, 'exportSubscribers'])->name('subscriptions.export');

    /// Bill Payment
    // Route::get('/bill_payment', [BillPaymentController::class, 'index'])->name('bill_payment');
    // Route::post('/bill_payment', [BillPaymentController::class, 'store'])->name('billpayments.store');
    // Route::post('/Dstvverify', [BillPaymentController::class, 'verify'])->name('Dstvverify');
    // Route::post('/Dstvvariations', [BillPaymentController::class, 'getVariations']);
    // Route::get('/Dstvhistory', [BillPaymentController::class, 'index']);

    // Route::post('/verify-electricity', [BillPaymentController::class, 'verifyElectricity'])->name('verify.electricity');
    // Route::get('/get-data-variations', [BillPaymentController::class, 'getDateVariations'])->name('get.data.variations');


    // Virtual Account
    // Route::get('/virtualCard', [VirtualAccountController::class, 'index'])->name('virtualCard');
    // Route::post('/virtualCard', [VirtualAccountController::class, 'createVirtualAccount'])->name('virtualCard.store');
    // Route::get('/allvirtualcard', [VirtualAccountController::class, 'allvirtualcard'])->name('allvirtualcard');
    // Route::delete('/virtualCard/{id}', [VirtualAccountController::class, 'destroy'])->name('virtualCard.destroy');


    // Chargeback
    // Route::get('/chargeback', [ChargebackController::class, 'index'])->name('chargeback');
    // Route::post('/chargeback/submitEvidence', [ChargebackController::class, 'submitEvidence'])->name('chargeback.submitEvidence');

});





    // Route::get('/admin/register', [RegisterController::class, 'index'])->name('admin.register');
    // Route::post('/admin/register', [RegisterController::class, 'store']);

    Route::get('/admin/login', [RegisterController::class, 'indexlogin'])->name('admin.login');
    Route::post('/admin/login', [RegisterController::class, 'login'])->name('admin.login.submit')->middleware('throttle:5,1');
    // Route::post('/admin/login', [AdminController::class, 'login'])->middleware('throttle:5,1');

    Route::middleware('admin.auth')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/transactionhistory', [AdminTransactionHistoryController::class, 'index'])->name('admin.transactionhistory');
        Route::delete('/admin/transactionhistory/{id}', [AdminTransactionHistoryController::class, 'destroy'])->name('transactionhistory.destroy');

        Route::get('/admin/business-account', [BusinessAccountController::class, 'index'])->name('admin.business-account');
        Route::get('/admin/business-account/{id}', [BusinessAccountController::class, 'find']);
        Route::post('/admin/business-account-status/{id}', [BusinessAccountController::class, 'updateStatus']);
        Route::get('/admin/business-account/deactivate/{id}', [BusinessAccountController::class,'deactivate']);
        Route::delete('/admin/business-account/delete/{id}', [BusinessAccountController::class,'destroy']);
        Route::get('/admin/business-account/edit/{id}', [BusinessAccountController::class,'edit']);
        Route::post('/admin/business-account/update/{id}', [BusinessAccountController::class,'update']);

        Route::get('/admin/personal-account', [PersonalAccountController::class, 'index'])->name('admin.personal-account');
        Route::get('/admin/personal-account/{id}', [PersonalAccountController::class, 'find']);
        Route::get('/admin/personal-account/deactivate/{id}', [PersonalAccountController::class,'deactivate']);
        Route::delete('/admin/personal-account/delete/{id}', [PersonalAccountController::class,'destroy']);
        Route::get('/admin/personal-account/edit/{id}', [PersonalAccountController::class,'edit']);
        Route::post('/admin/personal-account/update/{id}', [PersonalAccountController::class,'update']);

        Route::get('/admin/allbeneficias', [AllBeneficiasController::class, 'index'])->name('admin.allbeneficias');
        Route::get('/admin/allcustomer', [AllCustomersController::class, 'index'])->name('admin.allcustomer');
        Route::get('/admin/allaccount', [AllAccountController::class, 'index'])->name('admin.allaccount');
        Route::get('/admin/allsubaccount', [AllSubaccountController::class, 'index'])->name('admin.allsubaccount');
        Route::get('/admin/allteammembers', [AllTeamMembersController::class, 'index'])->name('admin.allteammembers');
        Route::get('/admin/allbillpayment', [AllBillPaymentController::class, 'index'])->name('admin.allbillpayment');

        Route::get('/admin/donation', [AllDonationController::class, 'index'])->name('admin.donation');
        Route::get('/admin/donation/{id}', [AllDonationController::class, 'show']);

        Route::get('/admin/payment', [AllPaymentController::class, 'index'])->name('admin.payment');
        Route::get('/admin/payment/{id}', [AllPaymentController::class, 'show']);

        Route::get('/admin/remita', [AllRemitaController::class, 'index'])->name('admin.remita');
        Route::get('/admin/remita/{id}', [AllRemitaController::class, 'show']);

        Route::get('/admin/subscription', [AllSubscriptionController::class, 'index'])->name('admin.subscription');
        Route::get('/admin/subscription/{id}', [AllSubscriptionController::class, 'show']);

        Route::get('/admin/invoice', [AllInvoiceController::class, 'index'])->name('admin.invoice');
        Route::get('/admin/invoice/{id}', [AllInvoiceController::class, 'show']);

        Route::get('/admin/refund', [AllRefundController::class, 'index'])->name('admin.refund');

        
        Route::get('/admin/chargeback', [AllChargebackController::class, 'index'])->name('admin.chargeback');
        Route::post('/admin/chargeback/{id}/update-status', [AllChargebackController::class, 'updateStatus'])->name('chargeback.updateStatus');
        Route::post('/admin/chargeback/submitEvidence', [AllChargebackController::class, 'submitEvidence'])->name('admin.chargeback.submitEvidence');



        Route::get('/admin/add_admin', [AddAdminController::class, 'index'])->name('admin.add_admin');
        Route::post('/admin/add_admin', [AddAdminController::class, 'store'])->name('admin.add_admin.store');
        Route::get('/admin/profile', [AddAdminController::class, 'indexprofile'])->name('admin.profile');
        Route::post('/admin/profile', [AddAdminController::class, 'updateprofile'])->name('admin.profile.update');
        Route::get('/admin/all_admin', [AddAdminController::class, 'indexadmin'])->name('admin.all_admin');
        Route::get('/admin/view/{id}', [AddAdminController::class, 'view'])->name('admin.view');
        Route::post('/admin/{id}/unlock', [AddAdminController::class, 'unlock'])->name('admin.unlock');
        Route::get('/admin/{id}/activity', [AddAdminController::class, 'activity'])->name('admin.activity');
        Route::get('/admin/reset-password', [AddAdminController::class, 'showResetForm'])->name('admin.reset.password');
        Route::post('/admin/reset-password', [AddAdminController::class, 'resetPassword']);

        Route::get('/admin/{id}/settings', [AddAdminController::class, 'settings'])->name('admin.settings');
Route::post('/admin/{id}/change-role', [AddAdminController::class, 'changeRole'])->name('admin.changeRole');
Route::post('/admin/{id}/lock', [AddAdminController::class, 'lock'])->name('admin.lock');
Route::post('/admin/{id}/unlock', [AddAdminController::class, 'unlock'])->name('admin.unlock');
Route::post('/admin/{id}/deactivate', [AddAdminController::class, 'deactivate'])->name('admin.deactivate');
Route::post('/admin/{id}/reset-password', [AddAdminController::class, 'resetPassword'])->name('admin.resetPassword');
Route::delete('/admin/{id}', [AddAdminController::class, 'destroy'])->name('admin.delete');



        Route::get('/admin/exchangerate', [SettingController::class, 'index'])->name('admin.exchangerate');
        Route::get('/admin/exchangerate/{id}/edit', [SettingController::class, 'edit'])->name('admin.exchangerate.edit');
        Route::put('/admin/exchangerate/{id}', [SettingController::class, 'update'])->name('admin.exchangerate.update');
        Route::delete('/admin/exchangerate/{id}', [SettingController::class, 'destroy'])->name('admin.exchangerate.destroy');
        Route::get('/admin/exchangerate/create', [SettingController::class, 'create'])->name('admin.exchangerate.create');
        // Route::post('/admin/exchangerate/store', [SettingController::class, 'store'])->name('admin.exchangerate.store');
Route::post('/admin/currency/store', [SettingController::class, 'storeCurrency'])->name('admin.currency.store');
Route::post('/admin/exchangerate/store', [SettingController::class, 'storeExchangeRate'])->name('admin.exchangerate.store');



        Route::get('/admin/career', [CareerController::class, 'create'])->name('admin.career.create');
        Route::post('/admin/career', [CareerController::class, 'store'])->name('admin.career.store');
        Route::get('/admin/career-view', [CareerController::class, 'index'])->name('admin.career.index');
        Route::delete('/admin/career-view/{id}',  [CareerController::class, 'destroy'])->name('admin.career-view.destroy');
        Route::get('/admin/career-view/{id}', [CareerController::class, 'edit'])->name('admin.career-view.edit');
        Route::put('/admin/career-view/{id}', [CareerController::class, 'update'])->name('admin.career-view.update');












        Route::post('/admin/logout', [DashboardController::class, 'logout'])->name('admin.logout');

















    });

