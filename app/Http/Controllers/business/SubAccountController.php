<?php

namespace App\Http\Controllers\business;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Subaccount;
use App\Models\Bank;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;

class SubAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function subaccount(Request $request)
    {
     
        $user = Auth::user();
        $subaccounts = Subaccount::where('user_id', $user->id)->get();
        $response = Http::withToken(env('OHENTPAY_API_KEY'))->get(rtrim(env('OHENTPAY_BASE_URL'), '/') . '/countries');
        $countries = [];

        if ($response->successful()) {
            $countries = $response->json();
            // dd($countries);
        }
        // Fetch all banks
        $banks = Bank::all();
        
        if ($request->expectsJson()) {
            if ($subaccounts->isEmpty()) {
                return response()->json(['message' => 'You have not added any subaccounts yet.'], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Bank accounts and countries retrieved successfully.',
                'data' => $subaccounts,
                'countries' => $countries
            ], 200);
        }

        if ($subaccounts->isEmpty()) {
            session()->flash('info', 'You have not added any subaccounts yet.');
        }
        // dd(' $banks');

        return view('business.subaccount', [
            'subaccounts' => $subaccounts,
            'countries' => $countries,
            'banks' => $banks
        ]);

        
    }

    public function store(Request $request)
    {
        $isDynamic = $request->input('formDynamicFields') === 'true';

        if (!$isDynamic) {
            $validated = $request->validate([
                'bank_name' => 'required|string|max:255',
                'type' => 'required|string|max:10',
                'bank_country' => 'required|string',
                'account_number' => ['required', 'regex:/^\d{10}$/'],
                'account_name' => 'required|string|max:255',
                'currency' => 'required|string|size:3', // ISO 4217
                'bic' => 'nullable|string|regex:/^[A-Za-z0-9]{8,11}$/',
                'iban' => 'nullable|string|max:34|regex:/^[A-Za-z0-9]+$/',
                'city' => 'nullable|string|max:255',
                'state' => 'nullable|string|max:255',
                'zipcode' => 'nullable|string|max:20',
                'recipient_address' => 'nullable|string|max:255',
            ]);
        } else {
            // Dynamic fields (AU_AUD, US_USD, etc.)
            $validated = $request->validate([
                'bank_country' => 'required|string|max:10',
                'currency' => 'required|string|size:3', // ISO 4217 currency code
                'bic' => 'required|string|size:8|regex:/^[A-Za-z0-9]{8,11}$/', // SWIFT/BIC
                'iban' => 'required|string|max:34|regex:/^[A-Za-z0-9]+$/', // IBAN format
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'zipcode' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'type' => 'required|string|max:10',
            ]);
        }

        $isFirst = Subaccount::where('user_id', Auth::id())->count() === 0;

        $subaccount = Subaccount::create([
            'user_id' => Auth::id(),
            'account_number' => isset($validated['account_number']) ? Crypt::encryptString($validated['account_number']) : "",
            'account_name' => $validated['account_name'] ?? null,
            'bank_name' => $validated['bank_name'] ?? "",
            'bank_country' => $validated['bank_country'] ?? "",
            'currency' => $validated['currency'],
            'bic' => $validated['bic'] ? Crypt::encryptString($validated['bic']) : null,
            'iban' => $validated['iban'] ? Crypt::encryptString($validated['iban']) : null,
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'zipcode' => $validated['zipcode'] ?? null,
            'recipient_address' => $validated['recipient_address'] ?? null,
            'type' => $validated['type'] ?? null,
            'default' => $isFirst,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Subaccount created successfully.',
                'data' => $subaccount
            ], 201);
        }

        // Web request response
        return redirect()->route('business.subaccount')->with('success', 'Subaccount created successfully.');
    }

    public function edit($id)
    {
        $subaccount = Subaccount::findOrFail($id);

        if ($subaccount->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $bankAccount = Subaccount::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereNull('deleted_at')->firstOrFail();

        // Fetch all countries
        $response = Http::withToken(env('OHENTPAY_API_KEY'))->get(rtrim(env('OHENTPAY_BASE_URL'), '/') . '/countries');
        $countries = [];

        if ($response->successful()) {
            $countries = $response->json();
            // dd($countries);
        }

        // Fetch all banks
        $banks = Bank::all();

        //api response
        if (request()->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $subaccount,
                'countries' => $countries,
                'banks' => $banks,
            ], 200);
        }

        $allUserSubAccounts = Subaccount::where('user_id', Auth::id())->get();

        return view('business.editSubaccount', compact('subaccount', 'allUserSubAccounts', 'countries', 'banks'));
    }


    public function show()
    {
        $user = Auth::user();
        $subaccounts = Subaccount::where('user_id', $user->id)->get();

        if ($subaccounts->isEmpty()) {
            return response()->json(['message' => 'No subaccounts found.'], 404);
        }

        return response()->json([
            'message' => 'Subaccounts retrieved successfully.',
            'data' => $subaccounts
        ]);
    }


    public function update(Request $request, $id)
    {
        $subaccount = Subaccount::find($id);

        // Check if subaccount exists and belongs to the authenticated user
        if (!$subaccount || $subaccount->user_id !== Auth::id()) {
            return response()->json(['message' => 'Subaccount not found or unauthorized.'], 403);
        }



        $validated = $request->validate([
            'bank_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\pL\s]+$/u',
                function ($attribute, $value, $fail) {
                    if (preg_match('/^\d+$/', $value)) {
                        $fail('The bank name cannot be just numbers.');
                    }
                }
            ],
            'bank_country' => 'required|string',
            'account_number' => ['required', 'regex:/^\d{10}$/'],
            'account_name' => 'required|string'
        ]);

        $subaccount->bank_name = $validated['bank_name'];
        $subaccount->bank_country = $validated['bank_country'];
        $subaccount->account_number = Crypt::encryptString($validated['account_number']);
        $subaccount->account_name = $validated['account_name'];
        $subaccount->save();

        return response()->json([
            'message' => 'Subaccount updated successfully.',
            'data' => $subaccount
        ]);
    }

    public function destroy($id)
    {
        $subaccount = Subaccount::find($id);

        // Check if subaccount exists and belongs to the authenticated user
        if (!$subaccount || $subaccount->user_id !== Auth::user()->id) {
            return response()->json(['message' => 'Subaccount not found or unauthorized.'], 403);
        }

        $subaccount->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Subaccount deleted successfully.',
        ]);
    }


    public function destroyAll()
    {
        $user = Auth::user();

        $deletedCount = Subaccount::where('user_id', $user->id)->delete();

        if($deletedCount === 0){
            return response()->json(["status" => "error", "message" => "No Subaccount to delete"], 404);
        }else{
            return response()->json([
                'status' => 'success',
                'message' => 'All your bank accounts have been deleted.',
                'deleted_count' => $deletedCount
            ], 200);
        }
    }

    public function setDefault(Request $request, $id)
    {
        $account = Subaccount::findOrFail($id);

        if ($account->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        Subaccount::where('user_id', Auth::id())->update(['default' => false]);
        $account->default = true;
        $account->save();

        return response()->json([
            'message' => 'Payout Sub Account Set.',
            'default_account_id' => $account->id,
        ]);
    }

    public function fetchlocalBanks(Request $request)
    {
        $countryCurrency = strtolower($request->get('countryCurrency')); // Default to NG
        $currency = strtolower($request->get('currency')); // Default to NGN

        try {
            $response = Http::withToken(env('OHENTPAY_API_KEY'))
                ->get(rtrim(env('OHENTPAY_BASE_URL'), '/') . '/bankfields', [
                    'country' => $countryCurrency,
                    'currency' => $currency
                ]);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Bank fields fetched successfully.',
                    'fields' => $response->json()
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch bank fields',
                'details' => $response->json()
            ], $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch bank fields',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function validatePayoutAccountName(Request $request)
    {
        $request->validate([
            'country' => 'required|string',
            'currency' => 'required|string',
            'bank_id' => 'required|string',
            'account_number' => 'required|string',
        ]);

        $payload = $request->only([
            'country', 'currency', 'bank_id', 'account_number'
        ]);

        $response = Http::withToken(env('OHENTPAY_API_KEY'))->post(
            rtrim(env('OHENTPAY_BASE_URL'), '/') . '/recipients/validate', $payload
        );

        $data = $response->json();

        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Payout account validated successfully.',
                'account_name' => $data['account_name'] ?? null,
                'data' => $data
            ], 200);
        }


        return response()->json([
            'status' => 'error',
            'message' => 'Invalid payout account.',
            'data' => $response->json()
        ], $response->status());
    }

}
