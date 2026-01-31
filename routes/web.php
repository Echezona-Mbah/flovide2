<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Business\addBankAccountController;
use App\Http\Controllers\Business\DashboardController;
use App\Http\Controllers\Business\SubAccountController;
use App\Http\Controllers\Business\TransactionHistoryController;
use App\Http\Controllers\Business\InvoicesController;
use App\Http\Controllers\MainPage\businessController;
use App\Http\Controllers\MainPage\SendMoneyHomePageController;
use App\Http\Controllers\MainPage\personalController;
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

Route::get('/send-money/{slug}', [SendMoneyHomePageController::class, 'index'])->name('send-money');

Route::get('/deletion', function () {
    return view('mainpage.deletion');
})->name('deletion');



Route::get('/dashboard/exchange-rate', function (\Illuminate\Http\Request $request) {
    $helper = new class {
        use \App\Traits\CurrencyHelper;
    };

    return response()->json(
        $helper->getExchangeRateFromMap($request->from, $request->to)
    );
});




Route::get('/dashboard', [DashboardController::class, 'create'])
    ->middleware(['auth', 'verified', \App\Http\Middleware\ResolveOwnerMiddleware::class])
    ->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->prefix('business')->name('business.')->group(function () {
    Route::get('/payout', [addBankAccountController::class, 'payouts'])->name('payouts');
    Route::post('/payout', [addBankAccountController::class, 'store'])->name('store');
    Route::put('bank-account/{id}', [addBankAccountController::class, 'update'])->name('update');
    Route::get('bank-account/{id}', [addBankAccountController::class, 'edit'])->name('edit');
    Route::delete('delete-account/{id}', [addBankAccountController::class, 'destroy'])->name('destroy');
    Route::post('/bank-accounts/{id}/set-default', [addBankAccountController::class, 'setDefault'])->name('setDefault');
    Route::delete('bank-accounts/delete-all', [addBankAccountController::class, 'destroyAll'])->name('destroyAll');

    //subaccount
    Route::get('/subaccount', [SubAccountController::class, 'subaccount'])->name('subaccount');
    Route::delete('delete-subaccounts/delete-all', [SubAccountController::class, 'destroyAll'])->name('destroyAll');
    Route::post('subaccounts', [SubAccountController::class, 'store'])->name('store');
    Route::get('edit-subaccount/{id}', [SubAccountController::class, 'edit'])->name('subaccountEdit');
    Route::delete('deleteSubaccount/{id}', [SubAccountController::class, 'destroy'])->name('destroy');
    Route::put('updateSubaccount/{id}', [SubAccountController::class, 'update'])->name('updateSubAccount');
    Route::post('/bankSubAccounts/{id}/set-default', [SubAccountController::class, 'setDefault'])->name('setDefault');

    //transaction history
    Route::get('/transactionHistory', [TransactionHistoryController::class, 'transaction'])->name('transactionHistory');


});

require __DIR__ . '/auth.php';
