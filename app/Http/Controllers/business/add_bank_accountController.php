<?php

namespace App\Http\Controllers\business;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class add_bank_accountController extends Controller
{
    //
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|size:10',
            'bank_country' => 'required|string|max:10',
            'bank_name' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        $bankAccount = BankAccount::create([
            'user_id' => $user->id,
            'account_name' => $validated['account_name'],
            'account_number' => Crypt::encryptString($validated['account_number']),
            'bank_country' => $validated['bank_country'],
            'bank_name' => $validated['bank_name'],
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

    public function show()
    {
        // Get all Bank Account that belong to the authenticated user
        $bankAccount = BankAccount::where('user_id', Auth::user()->id)->get();

        if ($bankAccount->isEmpty()) {
            return response()->json(['message' => 'No Bank Account found.'], 404);
        }

        return response()->json([
            'message' => 'Bank Accounts retrieved successfully.',
            'data' => $bankAccount,
        ]);
    }


    public function destroy($id)
    {
        $account = BankAccount::find($id);

        if (!$account) {
            return response()->json([
                'message' => 'Bank account not found.'
            ], 404);
        } else {

            if ($account->user_id !== Auth::user()->id) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            } else {

                $account->delete();

                return response()->json([
                    'message' => 'Bank account deleted successfully.'
                ], 200);
            }
        }
    }

    public function destroyAll()
    {
        $user = Auth::user();

        $deletedCount = BankAccount::where('user_id', $user->id)->delete();

        return response()->json([
            'message' => 'All your bank accounts have been deleted.',
            'deleted_count' => $deletedCount
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bank_name' => 'required|string',
            'bank_country' => 'required|string',
            'account_number' => 'required|digits:10',
            'account_name' => 'required|string',
        ]);

        $bankAccount = BankAccount::find($id);

        if (!$bankAccount) {
            return response()->json(['message' => 'Bank account not found.'], 404);
        } else {

            if ($bankAccount->user_id !== Auth::user()->id) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            } else {

                $bankAccount->update([
                    'bank_name' => $request->bank_name,
                    'bank_country' => $request->bank_country,
                    'account_number' => Crypt::encryptString($request->account_number),
                    'account_name' => $request->account_name,
                ]);

                return response()->json([
                    'message' => 'Bank account updated successfully.',
                    'data' => $bankAccount,
                ]);
            }
        }
    }
}
