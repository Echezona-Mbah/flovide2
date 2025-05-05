<?php

namespace App\Http\Controllers\business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subaccount;
use Illuminate\Support\Facades\Auth;

class SubAccountController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'account_number' => 'required|digits:10',
            'account_name' => 'required|string',
            'bank_name' => 'required|string',
            'bank_country' => 'required|string',
        ]);

        $subaccount = Subaccount::create([
            'user_id' => Auth::user()->id,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'bank_name' => $request->bank_name,
            'bank_country' => $request->bank_country,
        ]);

        return response()->json([
            'message' => 'Subaccount created successfully.',
            'data' => $subaccount
        ], 201);
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
                'bank_name', 'bank_country', 'account_number', 'account_name'
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
        } else {

            $subaccount->delete();

            return response()->json([
                'message' => 'Subaccount deleted successfully.',
            ]);
        }
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
}
