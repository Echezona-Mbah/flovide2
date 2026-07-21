<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\donations as donation;
use Illuminate\Support\Str;
use App\Models\Subaccount;

class donationsController extends Controller
{
    //
    public function index(){
        $user = Auth::guard('personal-api')->user();
        $donations = donation::where('personal_id', $user->id)->orderBy('created_at', 'desc')->paginate(10);
        
        if ($donations->total() < 1) {
            return response()->json([
                'data' => [
                    'status'  => 'empty',
                    'message' => 'No donations found for this user',
                    'donations' => []
                ]
            ]);
        }

        //return json
        return response()->json([
            'data' => [
                'status' => 'success',
                'message' => 'donations retrieved successfully',
                'donations' => $donations
            ]
        ], 201);
    }

    public function generateUniqueReference()
    {
        do {
            $reference = (string) Str::uuid();
        } while (donation::where('donation_reference', $reference)->exists());

        return $reference;
    }
    public function store(Request $request){
        $user = Auth::guard('personal-api')->user();

        // Validate request
        $validator = Validator::make($request->all(), [
            'cover_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // 5MB
            'title' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'visibility' => 'nullable|in:public,private',
            'percentage' => 'nullable|numeric|min:0|max:100',
            'subaccount_id' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ]
            ], 422);
        }

        //get subaccount details with personal_id and subaccount_id
        $subaccount = Subaccount::where('id', $request->input('subaccount_id'))
            ->where('personal_id', $user->id)
            ->first();
        if (!$subaccount) {
            if (request()->expectsJson()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'Invalid subaccount selected or unauthorised.'
                    ]
                ], 404);
            }
        }

        $path = null;
        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('donation_cover_image', 'public');
        }

        $donation_reference = $this->generateUniqueReference();
        //get page_link
        $pageLink = url('donation/donationcheckout/' . $donation_reference);

        // Create donation
        $donation = donation::create([
            'user_id' => null,
            'personal_id' => $user->id,
            'cover_image' => $path,
            'title' => $request->title,
            'donation_reference' => $donation_reference,
            'amount' => $request->amount,
            'currency' => $request->currency ?? 'NGN',
            'visibility' => $request->visibility ?? 'private',
            'page_link' => $pageLink,
            'percentage' => $request->percentage ?? null,
            'subaccount_id' => $request->subaccount_id ?? null,
            'subaccount' => $subaccount->bank_name ?? null,
            'subaccount_name' => $subaccount->account_name ?? null,
            'subaccount_number' => $subaccount->account_number ?? null,
        ]);

        return response()->json([
            'data' => [
                'status' => 'success',
                'message' => 'donation created successfully',
                'donations' => $donation
            ]
        ], 201);
    }

    public function update(Request $request, $id){
        $user = Auth::guard('personal-api')->user();

        $donation = donation::where('id', $id)
                        ->where('personal_id', $user->id)
                        ->first();

        if (!$donation) {
            return response()->json([
                'data' => [
                    'status'  => 'error',
                    'message' => 'donation not found or not authorized'
                ]
            ], 404);
        }

         // Validate fields
        $validator = Validator::make($request->all(), [
            'cover_image' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'amount' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'visibility' => 'nullable|in:public,private',
            'subaccount_id' => 'nullable|exists:subaccounts,id',
            'percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ]
            ], 422);
        }

        // Update only the provided fields
        $donation->update($request->only([
            'cover_image',
            'title',
            'amount',
            'currency',
            'visibility'
        ]));

        return response()->json([
            'data' => [
                'status' => 'success',
                'message' => 'donation updated successfully',
                'donation' => $donation
            ]
        ]);

    }

    public function show(Request $request, $id){
        $user = Auth::guard('personal-api')->user();
        $donation = donation::where('id', $id)->where('personal_id', $user->id)->first();

        if (!$donation) {
            return response()->json([
                'data' => [
                    'status'  => 'error',
                    'message' => 'donation not found or not authorized'
                ]
            ], 404);
        }

        return response()->json([
            'data' => [
                'status'  => 'success',
                'message' => 'donation retrieved successfully',
                'donation'    => $donation
            ]
        ]);
    }

    public function destory(Request $request, $id){
        $user = Auth::guard('personal-api')->user();
        $donation = donation::where('id', $id)->where('personal_id', $user->id)->first();

        if (!$donation) {
            return response()->json([
                'data' => [
                    'status'  => 'error',
                    'message' => 'donation not found or not authorized'
                ]
            ], 404);
        }

        $donation->delete();

        return response()->json([
            'data' => [
                'status'  => 'success',
                'message' => 'donation deleted successfully'
            ]
        ]);
    }

    public function donationrecords(){
        $user = Auth::guard('personal-api')->user();
        // Fetch donations with related records
        $donations = donation::where("personal_id", $user->id)->with('records')->paginate(10);

        return response()->json([
            'data' => [
                'status'  => 'success',
                'message' => 'donations with records retrieved successfully',
                'donations'    => $donations
            ]
        ]);
    }
}
