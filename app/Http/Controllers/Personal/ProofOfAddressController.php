<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;
use RuntimeException;

class ProofOfAddressController extends Controller
{
    public function uploadProofOfAddress(Request $request) {
        
        //Authentication
        $account =  Auth::guard('personal-api')->user();

        if (!$account) {
            return response()->json([
                'data' => [
                    'success' => false,
                    'message' => 'Unauthenticated',
                ]
            ], 401);
        }

        // Rate Limiting
        // Maximum of 5 upload attempts within 1 minute.
        $rateLimitKey = 'proof-address-upload:' . $account->id;

        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);

            return response()->json([
                'data' => [
                    'success' => false,
                    'message' => "Too many upload attempts. Please try again in {$seconds} seconds.",
                ]
            ], 429);
        }


        //Validate File
        try {
            $request->validate([
                'proof_address' => [
                    'required',
                    'file',
                    'mimes:jpg,jpeg,png,pdf',
                    'max:5120', // 5MB
                ],
            ]);
        } catch (ValidationException $e) {

            $message = $e->validator->errors()->first('proof_address');

            // Check if the file is larger than 5MB
            if ($request->hasFile('proof_address')) {
                $file = $request->file('proof_address');

                if ($file->getSize() > 5 * 1024 * 1024) {
                    return response()->json([
                        'data' => [
                            'success' => false,
                            'message' => 'The proof of address file must not be larger than 5MB.',
                        ]
                    ], 422);
                }
            }

            return response()->json([
                'data' => [
                    'success' => false,
                    'message' => $message,
                ]
            ], 422);
        }

        //Count Valid Upload Attempt
        RateLimiter::hit($rateLimitKey, 60);


        $file = $request->file('proof_address');

        //Prevent Simultaneous Uploads
        $lock = Cache::lock('proof-address-upload-lock:' . $account->id, 30);

        if (!$lock->get()) {
            return response()->json([
                'data' => [
                    'success' => false,
                    'message' => 'A proof of address upload is already being processed. Please wait and try again.',
                ]
            ], 429);
        }

        $newFilePath = null;
        $oldFilePath = null;


        try {

            $extension = strtolower($file->getClientOriginalExtension());

            //Generate Filename
            $filename = 'proof_address_' . $account->id . '_' . Str::uuid() . '.' . $extension;

            $directory = public_path('uploads/proof-address');

            //Create Directory If It Doesn't Exist
            if (!is_dir($directory)) {
                if (!mkdir($directory, 0755, true) && !is_dir($directory)) {
                    throw new RuntimeException(
                        'Unable to create proof of address upload directory.'
                    );
                }
            }

            //Save New File
            $file->move($directory, $filename);

            $newFilePath = $directory . DIRECTORY_SEPARATOR . $filename;

            //Remember Old File
            if ($account->proof_address) {
                $oldFilePath = public_path($account->proof_address);
            }


            //Database Transaction
            DB::beginTransaction();

            //update account
            $account->update([
                'proof_address' => 'uploads/proof-address/' . $filename,
                'proof_address_status' => 'under review',
            ]);

            //Commit Database Changes
            DB::commit();

            //Delete Old File AFTER Successful Database Commit
            if ($oldFilePath && $oldFilePath !== $newFilePath && file_exists($oldFilePath)) {
                if (!unlink($oldFilePath)) {
                    Log::warning('Unable to delete old proof of address file.', [
                        'personal_id' => $account->id,
                        'old_file' => $oldFilePath,
                    ]);
                }
            }

            //log Successful Upload
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

        } catch (Throwable $e) {

            //Rollback Database
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            // Delete Newly Uploaded File If Database Operation Failed
            if ($newFilePath && file_exists($newFilePath)) {
                unlink($newFilePath);
            }

            //log error
            Log::error('Proof of address upload error', [
                'personal_id' => $account->id ?? null,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'data' => [
                    'success' => false,
                    'message' => 'We could not upload your proof of address at this time. Please try again.',
                ],
            ], 500);
        } finally {

            //Release Lock
            optional($lock)->release();
        }
    }
}
