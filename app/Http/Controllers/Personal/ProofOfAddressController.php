<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Personal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProofOfAddressController extends Controller
{
    public function uploadProofOfAddress(Request $request) {
        try {
            $account =  Auth::guard('personal-api')->user();

            if (!$account) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated',
                ], 401);
            }

            $request->validate([
                'proof_address' => [
                    'required',
                    'file',
                    'mimes:jpg,jpeg,png,pdf',
                    'max:5120', // 5MB
                ],
            ]);

        
            // Delete the old document if one exists
            if ($account->proof_address) {
                $oldFile = public_path($account->proof_address);

                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            // Store the new document
            $file = $request->file('proof_address');

            $filename = 'proof_address_' . $account->id . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $directory = public_path('uploads/proof-address');

            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $file->move($directory, $filename);

            //update the account
            $account->update([
                'proof_address' => 'uploads/proof-address/' . $filename,
                'proof_address_status' => 'pending',
            ]);

            //log this action
            Log::info('Proof of address uploaded', [
                'personal_id' => $account->id,
                'proof_address' => $account->proof_address,
                'proof_address_status' => $account->proof_address_status,
            ]);

            return response()->json([
                'data' => [
                    'success' => true,
                    'message' => 'Proof of address uploaded successfully.',
                    'proof_address' => asset($account->proof_address),
                    'proof_address_status' => $account->proof_address_status,
                ],
            ], 200);

        } catch (\Exception $e) {

            Log::error('Proof of address upload error', [
                'personal_id' => $account->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'data' => [
                    'success' => false,
                    'message' => 'Failed to upload proof of address.',
                ],
            ], 500);
        }
    }
}
