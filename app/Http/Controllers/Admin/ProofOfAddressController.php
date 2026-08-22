<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Personal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProofOfAddressController extends Controller
{
    public function update(Request $request)
    {
        try {
            $validate = $request->validate([
                'id' => 'required',
            ]);
                        
            $status = $request->input('proof_address_status') ?? 0;

            if (!$status) {
                return redirect()->back()->with('error', 'Status field is required');
            }

            $user = Personal::findOrFail($request->id);
            $user->proof_address_status = $status;
            $user->save();

            //return response 
            return redirect()->back()->with('success', 'Proof of address updated successfully');
        } catch (\Exception $e) {
            Log::error('proof of address update error', [$e->getMessage()]);
            return redirect()->back()->with('error', 'Proof of address not updated');
        }
    }
}
