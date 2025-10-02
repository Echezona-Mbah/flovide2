<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Countries;
use App\Models\TeamMembers;
use App\Models\TransactionHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Traits\CurrencyHelper;

class DashboardController extends Controller
{
  use CurrencyHelper;
public function create()
{
    $countries = Countries::all();

    // Get the owner ID resolved by middleware
    $ownerId = session('owner_id');

    // dd($ownerId);

    // Fetch recent transactions for the owner
    $transactions = TransactionHistory::where('user_id', $ownerId)
        ->latest('created_at')
        ->take(5)
        ->get();

    // Fetch balances for the owner
    $balances = Balance::where('user_id', $ownerId)->get();

    foreach ($balances as $balance) {
        $balance->currency_meta = $this->getCountryCodeFromCurrency($balance->currency);
    }

    return view('dashboard', compact(
        'countries',
        'transactions',
        'balances'
    ));
}







    public function fetchBalances()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OHENTPAY_API_KEY'),
            'Accept' => 'application/json',
        ])->get(env('OHENTPAY_BASE_URL') . '/balances');
    
        if ($response->successful()) {
            return $response->json();
        }
    
        return [
            'error' => true,
            'message' => $response->body(),
            'status' => $response->status(),
        ];
    }


    public function getBalances()
    {
        $data = $this->fetchBalances();
    
        if (isset($data['error']) && $data['error']) {
            return view('balances', ['balances' => []]);
        }
    
        return view('balances', ['balances' => $data['balances'] ?? []]);
    }
    
    // private function getCountryCodeFromCurrency($currency)
    // {
    //     $map = [
    //         'NGN' => ['symbol' => '₦', 'country' => 'ng'],
    //         'USD' => ['symbol' => '$', 'country' => 'us'],
    //         'KES' => ['symbol' => 'KSh', 'country' => 'ke'],
    //         'GHS' => ['symbol' => '₵', 'country' => 'gh'],
    //         'ZAR' => ['symbol' => 'R', 'country' => 'za'],
    //         'GBP' => ['symbol' => '£', 'country' => 'gb'],
    //         'EUR' => ['symbol' => '€', 'country' => 'eu'],
    //         'CAD' => ['symbol' => 'C$', 'country' => 'ca'],
    //         'CZK' => ['symbol' => 'Kč', 'country' => 'cz'],
    //         'DKK' => ['symbol' => 'kr', 'country' => 'dk'],
    //         'AUD' => ['symbol' => 'A$', 'country' => 'au'],
    //         'SEK' => ['symbol' => 'kr', 'country' => 'se'],
    //         'RON' => ['symbol' => 'lei', 'country' => 'ro'],
    //         'PLN' => ['symbol' => 'zł', 'country' => 'pl'],
    //         'CHF' => ['symbol' => 'CHF', 'country' => 'ch'],
    //         'HUF' => ['symbol' => 'Ft', 'country' => 'hu'],
    //         'NOK' => ['symbol' => 'kr', 'country' => 'no'],
    //     ];
    
    //     return $map[strtoupper($currency)] ?? ['symbol' => '', 'country' => 'us'];
    // }
    
}
