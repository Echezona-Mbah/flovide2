<?php

use App\Http\Controllers\AccountInquiryController;
use App\Http\Controllers\API\V1\BalanceController;
use App\Http\Controllers\API\V1\BeneficiaryController;
use App\Http\Controllers\API\V1\CollectionController;
use App\Http\Controllers\API\V1\RateController;
use App\Http\Controllers\API\V1\ReferenceDataController;
use App\Http\Controllers\API\V1\TransactionController;
use App\Http\Controllers\API\V1\WebhookController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Blaaiz\BlaaizController;
use App\Http\Controllers\Blaaiz\BlaaizWebhookController;
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
use App\Http\Controllers\Business\AddMoneyController as BusinessAddMoneyController;
use App\Http\Controllers\Business\BillPaymentController;
use App\Http\Controllers\Business\ChargebackController;
use App\Http\Controllers\Business\ComplianceController;
use App\Http\Controllers\Business\CreateBankController;
use App\Http\Controllers\Business\NotificationController as BusinessNotificationController;
use App\Http\Controllers\Business\OrganizationController as BusinessOrganizationController;
use App\Http\Controllers\Business\SendMoneyController;
use App\Http\Controllers\Business\SubscriptionController;
use App\Http\Controllers\Business\VirtualAccountController;
use App\Http\Controllers\Personal\AddBeneficiariesController as PersonalAddBeneficiariesController;
use App\Http\Controllers\Personal\AddMoneyController;
use App\Http\Controllers\Personal\BillPaymentController as PersonalBillPaymentController;
use App\Http\Controllers\Personal\CreateBankController as PersonalCreateBankController;
use App\Http\Controllers\Personal\NotificationController;
use App\Http\Controllers\Personal\OrganizationController;
use App\Http\Controllers\Business\PaymentController;
use App\Http\Controllers\Personal\SendMoneyController as PersonalSendMoneyController;
use App\Http\Controllers\Personal\TransactionHistoryController as PersonalTransactionHistoryController;
use App\Http\Controllers\Personal\VirtualAccountController as PersonalVirtualAccountController;
use App\Http\Controllers\Business\DonationController;
use App\Http\Controllers\Business\TransactionPinController;
use App\Http\Controllers\Fidelity\FidelityWebhookController;
use App\Http\Controllers\Ibanq\IbanqBeneficiaryAccountController;
use App\Http\Controllers\Ibanq\IbanqBeneficiaryApprovalController;
use App\Http\Controllers\Ibanq\IbanqBeneficiaryController;
use App\Http\Controllers\Ibanq\IbanqPaymentController;
use App\Http\Controllers\Ibanq\IbanqReferenceController;
use App\Http\Controllers\Ibanq\IbanqWalletController;
use App\Http\Controllers\IbanqTestController;
use App\Http\Controllers\IbanqWebhookController;
use App\Http\Controllers\Orchard\OrchardController;
use App\Http\Controllers\Payaza\PayoutController;
use App\Http\Controllers\Personal\ProofOfAddressController;
use App\Http\Controllers\Personal\ComplianceController as PersonalComplianceController;
use App\Http\Controllers\Pivot\PivotController;
use App\Services\PivotService;
use App\Http\Controllers\Interac\InteracController;
use App\Http\Controllers\Interac\InteracAccountController;
use App\Http\Controllers\Personal\TransactionPinController as PersonalTransactionPinController;
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
Route::post('auth/verify-user-login-otp', [LoginController::class, 'verifyUserLoginOtp']);
Route::post('auth/resend-user-login-otp', [LoginController::class, 'resendUserLoginOtp']);
Route::get('/country', [RegisterController::class, 'getAllCountry']);
Route::post('/auth/forgot-password', [ForgetPasswordController::class, 'forgotPassword']);
Route::post('/auth/forget-verify-otp', [ForgetPasswordController::class, 'verifyOTP']);
Route::post('/auth/reset-password', [ForgetPasswordController::class, 'resetPasswordapi']);
Route::post('/auth/request-password-otp', [ForgetPasswordController::class, 'requestForgetPasswordOtp']);


// Route::get('/add_account', [CreateBankController::class, 'create'])->name('add_account.create');
// Route::get('/team/invite/{token}', [BusinessOrganizationController::class, 'showInviteForm'])->name('team.accept-invite');
Route::post('/team/invite/{token}', [BusinessOrganizationController::class, 'completeInvite']);

//invoice receipt link
Route::get('/invoices/receipts/{tracking_code}', [InvoicesController::class, 'showReceipt']);

Route::get('/ibanq-test', [IbanqTestController::class, 'test']);
Route::get('/ibanq/wallets', [IbanqWalletController::class, 'listWallets']);
Route::get('/ibanq/wallets/{walletId}', [IbanqWalletController::class, 'walletDetails']);
Route::get('/ibanq/wallets/{walletId}/transactions/{currency}', [IbanqWalletController::class, 'walletTransactions']);

Route::get('/ibanq/beneficiaries', [IbanqBeneficiaryController::class, 'listBeneficiaries']);
Route::post('/ibanq/beneficiaries', [IbanqBeneficiaryController::class, 'createBeneficiary']);
Route::patch('/ibanq/beneficiaries/{beneficiaryId}', [IbanqBeneficiaryController::class, 'updateBeneficiary']);
Route::get('/ibanq/beneficiaries/{beneficiaryId}', [IbanqBeneficiaryController::class, 'viewBeneficiary']);

Route::get('/ibanq/beneficiaries/to-approve', [IbanqBeneficiaryApprovalController::class, 'listBeneficiariesToApprove']);
Route::post('/ibanq/beneficiaries/{beneficiaryId}/reject', [IbanqBeneficiaryApprovalController::class, 'rejectBeneficiary']);
Route::get('/ibanq/beneficiaries/{beneficiaryId}/approval-details', [IbanqBeneficiaryApprovalController::class, 'viewApprovalDetails']);

Route::post('/ibanq/beneficiaries/{beneficiaryId}/accounts', [IbanqBeneficiaryAccountController::class, 'addAccount']);
Route::get('/ibanq/beneficiaries/{beneficiaryId}/accounts', [IbanqBeneficiaryAccountController::class, 'listAccounts']);
Route::delete('/ibanq/beneficiaries/{beneficiaryId}/accounts/{beneficiaryAccountId}', [IbanqBeneficiaryAccountController::class, 'deleteAccount']);

Route::post('/ibanq/payments', [IbanqPaymentController::class, 'createPayment']);
Route::get('/ibanq/payments', [IbanqPaymentController::class, 'listPayments']);
Route::get('/ibanq/payments/{paymentId}', [IbanqPaymentController::class, 'viewPaymentDetails']);

Route::post('/ibanq/payments/{paymentId}/approve', [IbanqPaymentController::class, 'approvePayment']);
Route::post('/ibanq/payments/{paymentId}/reject', [IbanqPaymentController::class, 'rejectPayment']);

Route::get('/ibanq/reference/bank-fields/{country}/{currency}', [IbanqReferenceController::class, 'getBankAccountRequirements']);
Route::get('/ibanq/reference/beneficiaries/{type}/{country}/{currency}', [IbanqReferenceController::class, 'getBeneficiaryRequirements']);

Route::post('/ifx/webhook', [IbanqWebhookController::class, 'handle']);


Route::post('/payaza/payout', [PayoutController::class, 'sendPayout']);
Route::get('/payaza/transaction-status', [PayoutController::class, 'transactionStatus']);
Route::post('/payaza/account-enquiry', [PayoutController::class, 'accountEnquiry']);
Route::get('/payaza/banks', [PayoutController::class, 'getBanks']);

Route::post('/orchard/payout',[OrchardController::class, 'debit']);
Route::post('/orchard/account-inquiry',[OrchardController::class, 'accountInquiry']);


   Route::post('/v1/customer', [InteracController::class, 'createCustomer']);
    Route::get('/v1/customer/{customerId}', [InteracController::class, 'getCustomer']);
    Route::put('/v1/customer/{customerId}', [InteracController::class, 'updateCustomer']);
    Route::patch('/v1/customer/{customerId}/enable', [InteracController::class, 'enableCustomer']);
    Route::patch('/v1/customer/{customerId}/disable', [InteracController::class, 'disableCustomer']);

    Route::post('/v1/customer/{customerId}/alias', [InteracController::class, 'createAlias']);
    Route::get('/v1/customer/{customerId}/alias', [InteracController::class, 'listAliases']);
    Route::get('/v1/customer/{customerId}/alias/{aliasId}', [InteracController::class, 'getAlias']);
    Route::delete('/v1/customer/{customerId}/alias/{aliasId}', [InteracController::class, 'deleteAlias']);

    Route::post('/v1/payment/options', [InteracController::class, 'retrievePaymentOptions']);
    Route::post('/v1/payment', [InteracController::class, 'initiatePayment']);
    Route::put('/v1/payment/{paymentRefId}', [InteracController::class, 'submitPayment']);
    Route::post('v1/payment/{paymentRefId}/reverse', [InteracController::class, 'reverseInitiatedPayment']);
    Route::post('/v1/payment/{paymentRefId}/cancel', [InteracController::class, 'cancelPayment']);
    Route::get('/v1/payment/{paymentRefId}', [InteracController::class, 'getPayment']);
    Route::get('/v1/payment', [InteracController::class, 'listPayments']);

    Route::post('/v1/request', [InteracController::class, 'createRequestPayment']);
    Route::get('/v1/request/{requestId}', [InteracController::class, 'getRequestPayment']);
    Route::post('/v1/request/{requestId}/cancel', [InteracController::class, 'cancelRequestPayment']);
    Route::post('/v1/request/receive', [InteracController::class, 'retrieveIncomingRequestPayment']);
    Route::post('/v1/request/receive/{networkRequestRefId}/decline', [InteracController::class, 'declineIncomingRequestPayment']);

    Route::post('/v1/{account_num}/eligibility', [InteracAccountController::class, 'eligibility']);
    Route::post('/v1/{account_num}/transaction', [InteracAccountController::class, 'transaction']);
    Route::post('/v1/{account_num}/{transaction_id}/reversal', [InteracAccountController::class, 'reversal']);

    Route::patch('/v1/fraud/status', [InteracController::class, 'updateFraudStatus']);





    Route::post('/sumsub/webhook', [ComplianceController::class, 'handle'])->name('sumsub.webhook');
    Route::post('/webhooks/blaaiz', [BlaaizWebhookController::class, 'handle']);
    Route::post('/webhooks/fidelity', [FidelityWebhookController::class, 'handle']);





Route::get('/test-pivot-auth', function(PivotService $pivot) {
    $response = $pivot->authenticate();
    return response()->json($response);
});

    // Route::post('/blaaiz/interac/initiate', [BlaaizController::class, 'initiateInteracMoneyRequest']);
    // Route::post('/blaaiz/interac/accept', [BlaaizController::class, 'acceptInteracMoneyRequest']);

        Route::post('/blaaiz/simulate/interac-webhook', [BlaaizController::class, 'simulateInteracWebhook'])
        ->name('blaaiz.simulate.interac');
        Route::post('/blaaiz/customer', [BlaaizController::class, 'createCustomer'])->name('blaaiz.customer.create');
        Route::get('/blaaiz/customers',          [BlaaizController::class, 'listCustomers'])->name('blaaiz.customers.list');
        Route::get('/blaaiz/customers/{id}',     [BlaaizController::class, 'getCustomer'])->name('blaaiz.customers.get');
        Route::get('/blaaiz/wallets', [BlaaizController::class, 'listWallets'])->name('blaaiz.wallets.list');
        Route::post('/blaaiz/payout', [BlaaizController::class, 'createPayout'])->name('blaaiz.payout.create');




Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/pivot/payment', [PivotController::class, 'sendPayment']);
    Route::post('/pivot/payment-status', [PivotController::class, 'queryPaymentStatus']);
    Route::post('/pivot/account-validation', [PivotController::class, 'accountValidation']);
    Route::post('/pivot/card-payment', [PivotController::class, 'cardPayment']);

    Route::post('/kyc/create-applicant',[ComplianceController::class,'createSumsubApplicant']);




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
    Route::get('/business/invoices', [InvoicesController::class, 'index']);
    Route::get('/business/invoices/{id}', [InvoicesController::class, 'show']);
    // Route::get('/business/invoices/{id}/edit', [InvoicesController::class, 'edit']);
    // Route::get('/business/invoices/create', [InvoicesController::class, 'create']);
    Route::post('/business/invoices', [InvoicesController::class, 'store']);
    Route::put('/business/invoices/{id}', [InvoicesController::class, 'update']);
    Route::delete('/business/invoices/{id}', [InvoicesController::class, 'destroy']);
    
    //refund
    Route::get('/business/refunds', [refundsController::class, 'index']);
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
    
    //donations
    Route::get('business/donation', [DonationController::class, 'donationIndex']);
    // Route::get('business/donation/create', [DonationController::class, 'donationCreate']);
    Route::get('business/donation/{id}/export', [DonationController::class, 'exportUserDonation']);
    Route::get('business/donation/edit/{id}', [DonationController::class, 'donationEdit']);
    Route::get('business/donation/refresh', [DonationController::class, 'donationRefresh']);
    Route::get('business/donation/records/{id}', [DonationController::class, 'donationRecords']);
    Route::put('business/donation/update/{id}', [DonationController::class, 'donationUpdate']);
    Route::post('business/donation/store', [DonationController::class, 'donationStore']);
    Route::delete('business/donation/{id}/destory', [DonationController::class, 'donationDestroy']);

    //payment
    Route::get('business/payment', [PaymentController::class, 'index']);
    Route::get('business/payment/create', [PaymentController::class, 'create']);
    Route::get('business/payment/{id}/export', [PaymentController::class, 'exportUserPayments']);
    Route::get('business/payment/{id}/edit', [PaymentController::class, 'edit']);
    Route::get('business/payment/refresh', [PaymentController::class, 'refresh']);
    Route::get('business/payment/refresh/{id}', [PaymentController::class, 'refreshDetails']);
    // Route::get('business/payment/{id}/paymentcheckout', [PaymentController::class, 'paymentcheckout']);
    Route::put('business/payment/{id}/update', [PaymentController::class, 'update']);
    Route::post('business/payment/store', [PaymentController::class, 'store']);
    Route::delete('business/payment/{id}/destory', [PaymentController::class, 'destroy']);
    

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
    Route::post('/fetchBanks', [AddBeneficiariesController::class, 'fetchBanks']);
    Route::post('/validate-account', [AddBeneficiariesController::class, 'validateRecipient']);
    Route::get('/fetchcountrylist', [AddBeneficiariesController::class, 'fetchcountrylist']);
    Route::get('beneficia/all', [AddBeneficiariesController::class, 'allBeneficia']);
    Route::get('/banks/filter', [AddBeneficiariesController::class,'banks']);
    Route::post('/banks', [AddBeneficiariesController::class, 'getBankAPI'])->name('api.banks');
    Route::post('/account-inquiry', [AccountInquiryController::class, 'unifiedAccountInquiry']);



    // api routes for Send Money details
    Route::post('/exchange-rate', [SendMoneyController::class, 'getExchangeRates']);
    Route::get('/exchange-rate/refresh', [SendMoneyController::class, 'refreshExchangeRates']);
    Route::post('/send', [SendMoneyController::class, 'sendTransaction'])->name('transactions.send');
    Route::post('/exchange-send', [SendMoneyController::class, 'exchangeSubmit']);
    Route::get('/send/currency-fee', [SendMoneyController::class, 'getCurrencyFee']);
    Route::get('/send/currency-fees', [SendMoneyController::class, 'getPayoutFees']);




    // api routes for Balance details
    Route::get('balances', [CreateBankController::class, 'index']);
    Route::get('/singlebalances', [CreateBankController::class, 'create']);
    Route::post('/createBalance', [CreateBankController::class, 'createBalance']);
    Route::post('/update_balance', [CreateBankController::class, 'UpdateBalance']);
    Route::get('/total-balance', [CreateBankController::class, 'getUserTotalBalance']);
    Route::get('/dashboardapi', [CreateBankController::class, 'dashboardapi']);
    Route::get('/business-balances/{id}/statement', [CreateBankController::class, 'statement']);
    Route::post('/business-balances/{id}/interac-autodeposit', [CreateBankController::class, 'saveInteracAutoDepositEmail']);
    Route::post('/interac/autodeposit/initiate', [BlaaizController::class, 'initiateAutoDeposit']);


    // api routes for Customers details
    // Route::get('/customers', [AddCustomerController::class, 'index']);
    // Route::post('/add-customers', [AddCustomerController::class, 'store']);
    // Route::post('/fetchBanks-customers', [AddCustomerController::class, 'fetchBanks']);
    // Route::post('/validate-account-customers', [AddCustomerController::class, 'validateRecipient']);
    // Route::get('/fetchcountrylist-customers', [AddCustomerController::class, 'fetchcountrylist']);
    // // Route::put('customers/{id}', [AddCustomerController::class, 'update'])->name('customers.update');
    // Route::delete('customers/{id}', [AddCustomerController::class, 'destroy'])->name('customers.destroy');
    
    // api routes for Subscriptions details
    Route::get('/subscriptions', [SubscriptionController::class, 'index']);
    Route::post('/add-subscriptions', [SubscriptionController::class, 'store']);
    Route::post('subscriptions/{id}', [SubscriptionController::class, 'update']);
    Route::delete('/subscriptions/{id}', [SubscriptionController::class, 'destroy']);
    Route::post('/subscriptions_payment', [SubscriptionController::class, 'storeSubscriptionRecord']);
    Route::get('/subscriptions/{id}/export', [SubscriptionController::class, 'exportSubscriberss'])->name('subscriptions.export');
    Route::get('/subscriptions_record/{id}', [SubscriptionController::class, 'subscriptionDetails']);

    // api routes for DSTV details
    Route::get('/Dstvvariations', [BillPaymentController::class, 'getVariations']);
    Route::post('/Dstvverify', [BillPaymentController::class, 'verify']);
    Route::post('/Dstvpay', [BillPaymentController::class, 'handleDstv']);
    Route::get('/Dstvhistory', [BillPaymentController::class, 'index']);

    // Elecricity
    Route::get('/electricityvariations', [BillPaymentController::class, 'getElectricityVariations']);
    Route::post('/electricity_verify', [BillPaymentController::class, 'verifyElectricity']);
    Route::post('/electricitypay', [BillPaymentController::class, 'handleElectricity']);
    Route::get('/billhistory', [BillPaymentController::class, 'getUserBillPayments']);


    Route::get('/service_id', [BillPaymentController::class, 'getDataServiceId']);
    Route::post('/date_variations', [BillPaymentController::class, 'getDateVariations']);
    Route::post('/dataypay', [BillPaymentController::class, 'handleData']);


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
    Route::post('/nin', [ComplianceController::class, 'handleNin']);
    Route::post('/proof_of_identity', [ComplianceController::class, 'handleProofOfIdentity']);
    Route::post('/ownership', [ComplianceController::class, 'handleOwnership']);
    Route::post('/organisational_chart', [ComplianceController::class, 'handleOrganisationalChart']);
    Route::post('/register_of_directors', [ComplianceController::class, 'handleRegisterOfDirectors']);
    Route::post('/formation_document', [ComplianceController::class, 'handleFormationDocument']);
    Route::get('/sumsub-token',[ComplianceController::class,'getSumsubToken']);
    Route::get('/compliance/status', [ComplianceController::class, 'status']);



    // Chargeback
    Route::get('/chargeback', [ChargebackController::class, 'index']);
    Route::post('/chargeback/submitEvidence', [ChargeBackController::class, 'submitEvidence'])->name('chargeback.submitEvidence');


    Route::post('/addMoney/interac/initiate', [BusinessAddMoneyController::class, 'topupWithInterac']);
    Route::post('/topup', [BusinessAddMoneyController::class, 'topupWithCard']);
    Route::get('/addMoney/currency-fee', [BusinessAddMoneyController::class, 'getCurrencyFee']);
    Route::get('/addMoney/currency-fees', [BusinessAddMoneyController::class, 'getCurrencyFees']);


    //notifications
    Route::get('/notifications', [BusinessNotificationController::class, 'index']);
    Route::get('/notifications/unread', [BusinessNotificationController::class, 'unread']);
    Route::put('/notifications/{id}/read', [BusinessNotificationController::class, 'markAsRead']);

    // Organization
    Route::post('/profile', [BusinessOrganizationController::class, 'updateProfile']);
    Route::post('/email', [BusinessOrganizationController::class, 'updateEmail']);
    Route::post('/deactivate-account', [BusinessOrganizationController::class, 'deactivateAccount']);

    Route::get('/team', [BusinessOrganizationController::class, 'index']);
    Route::post('/team', [BusinessOrganizationController::class, 'store']);
    Route::patch('/team/{id}', [BusinessOrganizationController::class, 'updateRole'])->name('members.updateRole');

    Route::get('/pin-status', [TransactionPinController::class, 'status']);
    Route::post('/pin-set', [TransactionPinController::class, 'setPin']);
    Route::post('/pin-update', [TransactionPinController::class, 'updatePin']);
    Route::post('/pin-reset', [TransactionPinController::class, 'resetPin']);








    
    // Route::get('/balances', [BalanceController::class, 'index']);
    // Route::post('/balances', [BalanceController::class, 'store']);
    // Route::get('/balances/{id}', [BalanceController::class, 'show']);
    // Route::patch('/balances/{id}', [BalanceController::class, 'update']);






});



    Route::get('/personal-addMoney/currency-fee', [AddMoneyController::class, 'getCurrencyFee']);

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
    Route::post('auth/verify-login-otp', [LoginController::class, 'verifyLoginOtp']);
    Route::post('auth/resend-login-otp', [LoginController::class, 'resendLoginOtp']);
    Route::post('/auth/forgot-password-personal', [ForgetPasswordController::class, 'forgotPasswordPersonal']);
    Route::post('/auth/forget-verify-otp-personal', [ForgetPasswordController::class, 'verifyOTPPersonal']);
    Route::post('/auth/reset-password-personal', [ForgetPasswordController::class, 'resetPasswordapiPersonal']);
    Route::post('/auth/request-forgetpassword-otp-personal', [ForgetPasswordController::class, 'requestForgetPasswordOtpPersonal']);

    Route::get('/kyc/token', [PersonalComplianceController::class,'getSumsubToken']);


    Route::group(['middleware' => ['auth:personal-api']], function () {
        Route::prefix('personal')->group(function () {


        
        Route::get('/kyc/token', [PersonalComplianceController::class,'getSumsubToken']);
        Route::post('/kyc/webhook', [PersonalComplianceController ::class,'handle']);
        Route::post('/nin', [PersonalComplianceController ::class,'handleNin']);
        Route::get('/compliance/status', [PersonalComplianceController::class, 'status']);
        Route::post('/bvn', [PersonalComplianceController::class, 'handleBvn']);
        // Route::post('/personal-complianceNin', [ComplianceController::class, 'handleNin']);


        Route::get('/personal-beneficias', [PersonalAddBeneficiariesController::class, 'index']);
        Route::post('/personal-add-baneficia', [PersonalAddBeneficiariesController::class, 'store']);
        Route::put('/personal-beneficias/{id}', [PersonalAddBeneficiariesController::class, 'update'])->name('beneficias.update'); 
        Route::delete('/personal-beneficias/{id}', [PersonalAddBeneficiariesController::class, 'destroy']);
        Route::post('/personal-fetchBanks', [PersonalAddBeneficiariesController::class, 'fetchBanks']);
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
        Route::post('/personal-createBalance', [PersonalCreateBankController::class, 'createBalance']);
        Route::post('/personal-update_balance', [PersonalCreateBankController::class, 'UpdateBalance']);
        Route::get('/personal-total-balance', [PersonalCreateBankController::class, 'getUserTotalBalance']);
        Route::get('/personal-dashboardapi', [PersonalCreateBankController::class, 'dashboardapi']);
        Route::get('/personal-balances/{id}/statement', [PersonalCreateBankController::class, 'statement']);
        Route::post('/personal-interac/autodeposit/initiate', [BlaaizController::class, 'initiateAutoDepositPersonal']);


        //request bank account api route 
        Route::post('/personal-bank-account-request', [PersonalCreateBankController::class, 'store']);


        //payouts 
        Route::post('/bank-account', [PersonaladdBankAccountController::class, 'store']);
        Route::post('/validate-payout-account-name', [PersonaladdBankAccountController::class, 'validatePayoutAccountName']);
        Route::get('/show-bank-accounts', [PersonaladdBankAccountController::class, 'payouts']);
        Route::get('/bank-account/{id}', [PersonaladdBankAccountController::class, 'edit']);
        Route::get('/fetchBanks', [PersonaladdBankAccountController::class, 'fetchlocalBanks']);
        Route::post('/payout/{id}/set-default', [PersonaladdBankAccountController::class, 'setDefault']);
        Route::delete('/delete-account/{id}', [PersonaladdBankAccountController::class, 'destroy']);
        Route::delete('/delete-accounts', [PersonaladdBankAccountController::class, 'destroyAll']);
        // Route::put('/bankAccount/{id}', [PersonaladdBankAccountController::class, 'update']);

        //subaccount
        Route::get('/subaccount', [PersonalSubAccountController::class, 'subaccount']);
        Route::get('/show-subaccount/{id}', [PersonalSubAccountController::class, 'edit']);
        Route::get('/show-subaccounts', [PersonalSubAccountController::class, 'show']);
        Route::get('/fetchBanks', [PersonalSubAccountController::class, 'fetchlocalBanks']);
        Route::delete('/deleteAllSubaccounts', [PersonalSubAccountController::class, 'destroyAll']);
        Route::delete('/deleteSubaccount/{id}', [PersonalSubAccountController::class, 'destroy']);
        Route::post('/subaccounts', [PersonalSubAccountController::class, 'store']);
        Route::post('/validateSubaccountName', [PersonalSubAccountController::class, 'validatePayoutAccountName']);
        // Route::put('/updateSubaccount/{id}', [PersonalSubAccountController::class, 'update']);

        //refund
        Route::get('/refunds', [PersonalrefundsController::class, 'index']);
        Route::get('/fetchRefund/{id}', [PersonalrefundsController::class, 'fetchRefund']);
        Route::post('/refunds', [PersonalrefundsController::class, 'store']);
        Route::post('/refunds/{id}/status', [PersonalrefundsController::class, 'updateStatus']);
        
        //payments
        Route::get('/payments', [paymentsController::class, 'index']);
        Route::get('/payments/show/{id}', [paymentsController::class, 'show']);
        Route::get('/payments/paymentrecords', [paymentsController::class, 'paymentrecords']);
        Route::get('/payments/paymentrecords/{id}', [paymentsController::class, 'records']);
        Route::get('/payments/subaccount', [paymentsController::class, 'subaccount']);
        Route::get('/payments/export', [paymentsController::class, 'exportUserPayments']);
        Route::get('/payments/refresh', [paymentsController::class, 'refresh']);
        Route::get('/payments/refresh/{id}', [paymentsController::class, 'refreshDetails']);
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
    
        // api routes for Send Money detailsdeactivateAccount
        Route::post('/personal-exchange-rate', [PersonalSendMoneyController::class, 'getExchangeRate']);
        Route::post('/personal-send', [PersonalSendMoneyController::class, 'sendTransaction'])->name('transactions.send');
        Route::post('/exchange-send', [PersonalSendMoneyController::class, 'exchangeSubmit']);



        // Update Profile
        Route::post('/personal-profile', [OrganizationController::class, 'updateProfile']);
        Route::post('/personal-email', [OrganizationController::class, 'updateEmail']);
        Route::post('/personal-deactivate-account', [OrganizationController::class, 'deactivateAccount']);


        Route::post('/personal-addMoney/interac/initiate', [AddMoneyController::class, 'topupWithInteracc']);
        // Route::get('/personal-addMoney/currency-fee', [AddMoneyController::class, 'getCurrencyFee']);

        Route::post('/personal-topup', [AddMoneyController::class, 'topupWithCard']);



        Route::get('/personal-transactions', [PersonalTransactionHistoryController::class, 'personalTransactions']);
        Route::get('/personal-transactions/{status}', [PersonalTransactionHistoryController::class, 'filterPersonalTransactions']);
        Route::get('/personal-personalTransactions', [PersonalTransactionHistoryController::class, 'showAllTransactions']);


        Route::get('/personal-notifications', [NotificationController::class, 'index']);
        Route::get('/personal-notifications/unread', [NotificationController::class, 'unread']);
        Route::put('/personal-notifications/{id}/read', [NotificationController::class, 'markAsRead']);

        //proof of address
        Route::post('/personal-proof-of-address', [ProofOfAddressController::class, 'uploadProofOfAddress']);


        Route::get('/personal-pin-status', [PersonalTransactionPinController::class, 'status']);
        Route::post('/personal-pin-set', [PersonalTransactionPinController::class, 'setPin']);
        Route::post('/personal-pin-update', [PersonalTransactionPinController::class, 'updatePin']);
        Route::post('/personal-pin-reset', [PersonalTransactionPinController::class, 'resetPin']);









    });
});






Route::prefix('v1')
    ->middleware('ip.whitelist')
    ->group(function () {
        Route::get('/webhooks', [WebhookController::class, 'index']);
        Route::post('/webhooks', [WebhookController::class, 'store']);
        Route::post('/webhooks/regenerate-secret', [WebhookController::class, 'regenerateSecret']);

        Route::get('/balances', [BalanceController::class, 'index']);
        Route::post('/balances', [BalanceController::class, 'store']);
        Route::get('/balances/{id}', [BalanceController::class, 'show']);

        Route::get('/beneficiaries', [BeneficiaryController::class, 'index']);
        Route::post('/beneficiaries', [BeneficiaryController::class, 'store']);
        Route::post('/beneficiaries/account-inquiry', [BeneficiaryController::class, 'accountInquiry']);
        Route::get('/beneficiaries/{id}', [BeneficiaryController::class, 'show']);
        Route::delete('/beneficiaries/{id}', [BeneficiaryController::class, 'destroy']);

        Route::get('/transactions', [TransactionController::class, 'index']);
        Route::get('/transactions/{id}', [TransactionController::class, 'show']);
        Route::post('/transactions', [TransactionController::class, 'store']);

        Route::get('/rates', [RateController::class, 'getExchangeRates']);

        Route::get('/reference-data', [ReferenceDataController::class, 'index']);
        Route::get('/reference-data/currencies', [ReferenceDataController::class, 'currencies']);
        Route::get('/reference-data/banks', [ReferenceDataController::class, 'banks']);

        Route::post('/collections/interac', [CollectionController::class, 'collectInterac']);
    });


Route::prefix('test/v1')
    ->middleware('ip.whitelist')
    ->group(function () {
        Route::get('/webhooks', [WebhookController::class, 'index']);
        Route::post('/webhooks', [WebhookController::class, 'store']);
        Route::post('/webhooks/regenerate-secret', [WebhookController::class, 'regenerateSecret']);

        Route::get('/balances', [BalanceController::class, 'index']);
        Route::post('/balances', [BalanceController::class, 'store']);
        Route::get('/balances/{id}', [BalanceController::class, 'show']);

        Route::get('/beneficiaries', [BeneficiaryController::class, 'index']);
        Route::post('/beneficiaries', [BeneficiaryController::class, 'store']);
        Route::post('/beneficiaries/account-inquiry', [BeneficiaryController::class, 'accountInquiry']);
        Route::get('/beneficiaries/{id}', [BeneficiaryController::class, 'show']);
        Route::delete('/beneficiaries/{id}', [BeneficiaryController::class, 'destroy']);

        Route::get('/transactions', [TransactionController::class, 'index']);
        Route::get('/transactions/{id}', [TransactionController::class, 'show']);
        Route::post('/transactions', [TransactionController::class, 'store']);

        Route::get('/rates', [RateController::class, 'getExchangeRates']);

        Route::get('/reference-data', [ReferenceDataController::class, 'index']);
        Route::get('/reference-data/currencies', [ReferenceDataController::class, 'currencies']);
        Route::get('/reference-data/banks', [ReferenceDataController::class, 'banks']);

        Route::post('/collections/interac', [CollectionController::class, 'collectInterac']);

    });
// git filter-branch --force --index-filter "git rm --cached --ignore-unmatch routes/api.php" --prune-empty --tag-name-filter cat -- --all

