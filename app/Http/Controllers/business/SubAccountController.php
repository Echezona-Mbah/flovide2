<?php

namespace App\Http\Controllers\business;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Subaccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class SubAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function subaccount()
    {
     
        $user = Auth::user();
        $subaccounts = Subaccount::where('user_id', $user->id)->get();

        if ($subaccounts->isEmpty()) {
            session()->flash('info', 'You have not added any subaccounts yet.');
        }
        // dd(' $user');

        return view('business.subaccount', [
            'subaccounts' => $subaccounts
        ]);

        
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_number' => ['required', 'regex:/^\d{10}$/'],
            'account_name' => 'required|string',
            'bank_name' => 'required|string',
            'bank_country' => 'required|string',
        ]);

        $isFirst = Subaccount::where('user_id', Auth::id())->count() === 0;

        $subaccount = Subaccount::create([
            'user_id' => Auth::id(),
            'account_number' => Crypt::encryptString($validated['account_number']),
            'account_name' => $validated['account_name'],
            'bank_name' => $validated['bank_name'],
            'bank_country' => $validated['bank_country'],
            'default' => $isFirst,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
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

        $allUserSubAccounts = Subaccount::where('user_id', Auth::id())->get();

        return view('business.editSubaccount', compact('subaccount', 'allUserSubAccounts'));
    }


    public function show($id)
    {
        $subaccount = Subaccount::find($id);

        // Check if subaccount exists and belongs to the authenticated user
        if (!$subaccount || $subaccount->user_id !== Auth::user()->id) {
            return response()->json(['message' => 'Subaccount not found or unauthorized.'], 403);
        } else {

            return response()->json([
                'message' => 'Subaccount retrieved successfully.',
                'data' => $subaccount
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $subaccount = Subaccount::find($id);

        // Check if subaccount exists and belongs to the authenticated user
        if (!$subaccount || $subaccount->user_id !== Auth::user()->id) {
            return response()->json(['message' => 'Subaccount not found or unauthorized.'], 403);
        } else {

            // Validate and update the subaccount
            $request->validate([
                'bank_name' => 'required|string',
                'bank_country' => 'required|string',
                'account_number' => 'required|digits:10',
                'account_name' => 'required|string'
            ]);

            // $subaccount->update($request->all());
            $subaccount->update($request->only([
                'bank_name',
                'bank_country',
                'account_number',
                'account_name'
            ]));

            return response()->json([
                'message' => 'Subaccount updated successfully.',
                'data' => $subaccount
            ]);
        }
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

        return response()->json([
            'message' => 'All your bank accounts have been deleted.',
            'deleted_count' => $deletedCount
        ], 200);
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
}
