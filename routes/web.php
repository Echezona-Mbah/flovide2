<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Business\addBankAccountController;
use App\Http\Controllers\Business\ComplianceController;
use App\Http\Controllers\Business\DashboardController;
use App\Http\Controllers\Business\SubAccountController;
use App\Http\Controllers\Business\TransactionHistoryController;
use App\Http\Controllers\Business\InvoicesController;
use App\Http\Controllers\Business\SendMoneyController;
use App\Http\Controllers\IbanqTestController;
use App\Http\Controllers\MainPage\businessController;
use App\Http\Controllers\MainPage\SendMoneyHomePageController;
use App\Http\Controllers\MainPage\BlogController;
use App\Http\Controllers\MainPage\personalController;
use App\Http\Controllers\MainPage\DeveloperController;
use App\Http\Controllers\Pivot\PivotController as PivotPivotController;
use App\Http\Controllers\PivotController;
use App\Models\Career;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

Route::get('/artisan-clear', function () {
    try {
        Artisan::call('optimize:clear');
        return response()->json([
            'status'  => 'success',
            'message' => 'Artisan optimize:clear command executed successfully.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status'  => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/business', [businessController::class, 'business'])->name('business');
Route::get('/', [personalController::class, 'personal'])->name('personal');
//careers route
Route::get('/careers', function () {
    $jobs = Career::where('status', 1)->latest()->get(); 
    return view('mainpage.careers', compact('jobs'));
})->name('careers');

//contact_us route
Route::get('/contactUs', function () {
    return view('mainpage.contactUs');
})->name('contactUs');

Route::get('/privacy-policy', function () {
    return view('mainpage.privacy-policy');
})->name('privacy-policy');

Route::get('/terms-condition', function () {
    return view('mainpage.terms-condition');
})->name('terms-condition');


Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/developer', [DeveloperController::class, 'index'])->name('developer');

Route::get('/deletion', function () {
    return view('mainpage.deletion');
})->name('deletion');



Route::get('/Coming', function () {
    return view('mainpage.comesoon');
})->name('Coming');

// Route::get('/test-fcm', function (\App\Services\FirebaseNotificationService $fcm) {
//     $user = \App\Models\User::whereNotNull('device_token')
//         ->where('device_token', '!=', '')
//         ->first();

//     abort_if(!$user, 404, 'No token found');

//     $ok = $fcm->sendToToken(
//         $user->device_token,
//         'FCM Test',
//         'If you see this, push is working.',
//         ['type' => 'test']
//     );

//     return [
//         'success' => $ok,
//         'user_id' => $user->id,
//         'has_token' => !empty($user->device_token),
//     ];
// });
    

Route::get('/send-money/{slug}', [SendMoneyHomePageController::class, 'index'])->name('send-money');
Route::get('/cron/payaza-check', [SendMoneyController::class, 'runPayazaCheck']);



Route::get('/kyc-verification', function () {
    return view('business.sumsub');
})->name('sumsub.kyc');
    Route::get('/sumsub-token',[ComplianceController::class,'getSumsubToken']);


Route::get('/dashboard', [DashboardController::class, 'create'])
    ->middleware(['auth', 'verified', \App\Http\Middleware\ResolveOwnerMiddleware::class])
    ->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->prefix('business')->name('business.')->group(function () {


    //transaction history
    Route::get('/transactionHistory', [TransactionHistoryController::class, 'transaction'])->name('transactionHistory');


});

// Route::prefix('v1/interac')->group(function () {
//     Route::post('/customer', [InteracController::class, 'createCustomer']);
//     Route::get('/customer/{customerId}', [InteracController::class, 'getCustomer']);
//     Route::put('/customer/{customerId}', [InteracController::class, 'updateCustomer']);
//     Route::patch('/customer/{customerId}/enable', [InteracController::class, 'enableCustomer']);
//     Route::patch('/customer/{customerId}/disable', [InteracController::class, 'disableCustomer']);

//     Route::post('/customer/{customerId}/alias', [InteracController::class, 'createAlias']);
//     Route::get('/customer/{customerId}/alias', [InteracController::class, 'listAliases']);
//     Route::get('/customer/{customerId}/alias/{aliasId}', [InteracController::class, 'getAlias']);
//     Route::delete('/customer/{customerId}/alias/{aliasId}', [InteracController::class, 'deleteAlias']);

//     Route::post('/payment/options', [InteracController::class, 'retrievePaymentOptions']);
//     Route::post('/payment', [InteracController::class, 'initiatePayment']);
//     Route::put('/payment/{paymentRefId}', [InteracController::class, 'submitPayment']);
//     Route::post('/payment/{paymentRefId}/reverse', [InteracController::class, 'reverseInitiatedPayment']);
//     Route::post('/payment/{paymentRefId}/cancel', [InteracController::class, 'cancelPayment']);
//     Route::get('/payment/{paymentRefId}', [InteracController::class, 'getPayment']);
//     Route::get('/payment', [InteracController::class, 'listPayments']);

//      Route::post('/request', [InteracController::class, 'createRequestPayment']);
//     Route::get('/request/{requestId}', [InteracController::class, 'getRequestPayment']);
//     Route::post('/request/{requestId}/cancel', [InteracController::class, 'cancelRequestPayment']);
//     Route::post('/request/receive', [InteracController::class, 'retrieveIncomingRequestPayment']);
//     Route::post('/v1/interac/request/receive/{networkRequestRefId}/decline', [InteracController::class, 'declineIncomingRequestPayment']);

    //   Route::post('{account_num}/eligibility', [InteracAccountController::class, 'eligibility']);
    // Route::post('{account_num}/transaction', [InteracAccountController::class, 'transaction']);
    // Route::post('{account_num}/{transaction_id}/reversal', [InteracAccountController::class, 'reversal']);

//     Route::patch('/v1/interac/fraud/status', [InteracController::class, 'updateFraudStatus']);


// });

require __DIR__ . '/auth.php';
