<?php

namespace App\Http\Controllers\Orchard;

use App\Http\Controllers\Controller;
use App\Services\OrchardService;
use Illuminate\Http\Request;
use Carbon\Carbon;


class OrchardController extends Controller
{
    public function debit(Request $request, OrchardService $orchard)
    {
        $request->validate([
            'customer_number' => 'required|string',
            'amount'          => 'required|numeric',
            'network'         => 'required|string',
        ]);

        $payload = [
            "customer_number" => $request->customer_number,
            "amount"          => number_format($request->amount, 2, '.', ''),
            "exttrid"         => uniqid('ORC_'),
            "reference"       => "Wallet Payment",
            "nw"              => $request->network,
            "trans_type"      => "MTC",
            "callback_url"    => route('transactionHistory'),
            "service_id"      => env('ORCHARD_SERVICE_ID'),
            "ts"              => now()->utc()->format('Y-m-d H:i:s'),
        ];

        $response = $orchard->sendPayment($payload);

        return response()->json($response);
    }



public function accountInquiry(Request $request, OrchardService $orchard)
{
    $request->validate([
        'customer_number' => 'required|string|max:20',
        'bank_code'       => 'required|string|max:3',
    ]);

    $payload = [
        "customer_number" => $request->customer_number,
        "exttrid"         => uniqid('AII_'),
        "service_id"      => env('ORCHARD_SERVICE_ID'),
        "nw"              => "BNK",
        "bank_code"       => $request->bank_code,
        "trans_type"      => "AII",
        "ts"              => now()->utc()->format('Y-m-d H:i:s')
    ];

    $response = $orchard->accountInquiry($payload);

    return response()->json($response);
}



public function appMobileAccountInquiry(Request $request, OrchardService $orchard)
{
    $request->validate([
        'customer_number' => 'required|string|max:20',
        'bank_code'       => 'required|string|max:3',
    ]);

    $payload = [
        "customer_number" => $request->customer_number,
        "exttrid"         => uniqid('APPM_'),
        "service_id"      => env('ORCHARD_SERVICE_ID'),
        "nw"              => "BNK",
        "bank_code"       => $request->bank_code,
        "trans_type"      => "AII",
        "ts"              => now()->utc()->format('Y-m-d H:i:s')
    ];

    $response = $orchard->accountInquiry($payload);

    return response()->json($response);
}



}