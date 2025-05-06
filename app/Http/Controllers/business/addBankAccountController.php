<?php

namespace App\Http\Controllers\business;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\BankAccount;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class addBankAccountController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:' . (request()->is('api/*') ? 'sanctum' : 'web'));
    }

    public function payouts(Request $request)
    {
        $user = Auth::user();
        $bankAccounts = BankAccount::where('user_id', $user->id)->get();

        if ($request->expectsJson()) {
            if ($bankAccounts->isEmpty()) {
                return response()->json(['message' => 'No bank account found.'], 404);
            }

            return response()->json([
                'message' => 'Bank accounts retrieved successfully.',
                'data' => $bankAccounts,
            ], 200);
        }

        return view('business.payouts', ['bankAccounts' => $bankAccounts]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_name' => 'required|string|max:255',
            'account_number' => ['required', 'regex:/^\d{10}$/'],
            'bank_country' => 'required|string|max:10',
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
        ]);

        $user = Auth::user();

        // Check if user already has a bank account
        $isFirst = BankAccount::where('user_id', $user->id)->count() === 0;

        $bankAccount = BankAccount::create([
            'user_id' => $user->id,
            'account_name' => $validated['account_name'],
            'account_number' => Crypt::encryptString($validated['account_number']),
            'bank_country' => $validated['bank_country'],
            'bank_name' => $validated['bank_name'],
            'default' => $isFirst
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Bank account added successfully.',
            'data' => [
                'id' => $bankAccount->id,
                'account_name' => $bankAccount->account_name,
                'account_number' => substr($validated['account_number'], -4),
                'bank_name' => $bankAccount->bank_name,
            ]
        ], 201);
    }

    // public function show()
    // {
    //     $bankAccount = BankAccount::where('user_id', Auth::user()->id)->get();

    //     if ($bankAccount->isEmpty()) {
    //         return response()->json(['message' => 'No Bank Account found.'], 404);
    //     } else {

    //         return response()->json([
    //             'message' => 'Bank Accounts retrieved successfully.',
    //             'data' => $bankAccount,
    //         ]);
    //     }
    // }


    public function destroy($id)
    {
        $account = BankAccount::find($id);

        if (!$account) {
            return response()->json([
                'message' => 'Bank account not found.'
            ], 404);
        }

        if ($account->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 403);
        }

        $account->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Bank account deleted successfully.'
        ], 200);
    }



    public function destroyAll()
    {
        $user = Auth::user();

        $deletedCount = BankAccount::where('user_id', $user->id)->delete();

        if ($deletedCount->isEmpty()) {
            return response()->json(['message' => 'No accounts to delete.'], 404);
        } else {

            return response()->json([
                'message' => 'All your bank accounts have been deleted.',
                'deleted_count' => $deletedCount
            ], 200);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bank_name' => 'required|string',
            'bank_country' => 'required|string',
            'account_number' => ['required', 'regex:/^\d{10}$/'],
            'account_name' => 'required|string',
        ]);

        $bankAccount = BankAccount::find($id);

        if (!$bankAccount) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Bank account not found.'], 404);
            }
            return redirect()->back()->with('error', 'Bank account not found.');
        }

        if ($bankAccount->user_id !== Auth::id()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        // Update the bank account
        $bankAccount->update([
            'bank_name' => $request->bank_name,
            'bank_country' => $request->bank_country,
            'account_number' => Crypt::encryptString($request->account_number),
            'account_name' => $request->account_name
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Bank account updated successfully.',
                'data' => $bankAccount
            ]);
        }

        return redirect()->route('business.edit', $id)->with('success', 'Bank account updated successfully.');
    }


    public function edit($id)
    {
        $bankAccount = BankAccount::findOrFail($id);

        if ($bankAccount->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $allUserAccounts = BankAccount::where('user_id', Auth::id())->get();

        return view('business.editPayout', compact('bankAccount', 'allUserAccounts'));
    }

    public function setDefault(Request $request, $id)
    {
        $account = BankAccount::findOrFail($id);

        if ($account->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        BankAccount::where('user_id', Auth::id())->update(['default' => false]);
        $account->default = true;
        $account->save();

        return response()->json([
            'message' => 'Payout account set.',
            'default_account_id' => $account->id,
        ]);
    }
}
