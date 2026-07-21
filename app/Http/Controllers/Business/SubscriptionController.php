<?php

namespace App\Http\Controllers\Business;

use App\Exports\SubscriptionRecordsExport;
use App\Http\Controllers\Controller;
use App\Models\Countries;
use App\Models\Subaccount;
use App\Models\Subscription;
use App\Models\SubscriptionRecord;
use App\Models\TeamMembers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;


class SubscriptionController extends Controller
{
    // public function index(Request $request)
    // {
    //     $user = auth()->user();
    //     $team = TeamMembers::where('user_id', $user->id)->first();
    //     $ownerId = $team ? $team->owner_id : $user->id;

    //     // $subscriptions = Subscription::where('user_id', $request->user()?->id ?? auth()->id())->get();
    //     $subscriptions = Subscription::where('user_id', $ownerId)->get();
    //     $subaccounts = Subaccount::where('user_id', $user->id)->get();

    //     if ($request->expectsJson()) {
    //         return response()->json([
    //             'data' => $subscriptions
    //         ], 200);
    //     }

    //     return view('business.subscription', compact('subscriptions','subaccounts')); // Optional Blade view
    // }
public function index(Request $request)
{
    $user = auth()->user();
    $team = TeamMembers::where('user_id', $user->id)->first();
    $ownerId = $team ? $team->owner_id : $user->id;

    // Fetch subscriptions and subaccounts
    $subscriptions = Subscription::where('user_id', $ownerId)->get();
    $subaccounts = Subaccount::where('user_id', $user->id)->get();

    $subscriberQuery = SubscriptionRecord::where('user_id', $user->id);

    if ($request->has('start_date')) {
        $subscriberQuery->whereDate('start_date', '>=', $request->start_date);
    }
    if ($request->has('end_date')) {
        $subscriberQuery->whereDate('end_date', '<=', $request->end_date);
    }
    if ($request->has('is_expired')) {
        $subscriberQuery->where('is_expired', $request->is_expired);
    }

    $subscribers = $subscriberQuery->get();

    // Calculate stats from SubscriptionRecord
    $subscriberCount = $subscribers->where('is_expired', 0)->count(); 
    $unsubscriberCount = $subscribers->where('is_expired', 1)->count();
    $totalSubscribers = $subscribers->count(); 
    $planCount = $subscriptions->count(); 
    $subscriberGrowth = 36; // Keep as static or calculate dynamically

    // API Response
    if ($request->expectsJson()) {
        return response()->json([
            'status' => 'success',
            'data' => $subscriptions,
            'stats' => [
                'subscriberCount' => $subscriberCount,
                'subscriberGrowth' => $subscriberGrowth,
                'planCount' => $planCount,
                'unsubscriberCount' => $unsubscriberCount,
                'totalSubscribers' => $totalSubscribers
            ]
        ], 200);
    }

    // Web view
    return view('business.subscription', compact(
        'subscriptions',
        'subaccounts',
        'subscriberCount',
        'subscriberGrowth',
        'planCount',
        'unsubscriberCount',
        'totalSubscribers'
    ));
}






    public function create() {
             $user = auth()->user();
        $team = TeamMembers::where('user_id', $user->id)->first();
        $ownerId = $team ? $team->owner_id : $user->id;
        $countries = Countries::all();
        $subaccounts = Subaccount::where('user_id', $user->id)->get();
        
            // Fetch subscription records (subscribers) for this subscription
    $subscribers = SubscriptionRecord::where('user_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->get();


        return view('business.add_subscription',compact('countries','subaccounts','subscribers'));
    }
    


    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'subscription_interval' => 'required|in:daily,weekly,monthly,yearly',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'subaccount_id' => 'required|string',
            'percentage' => 'required|numeric|min:0|max:100',
            'visibility' => 'required|in:public,private',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

            //get subaccount details
            $subaccount = Subaccount::find($request->subaccount_id);
                // dd($subaccount);

            if (!$subaccount) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'data' => [
                            'status' => 'error',
                            'message' => 'Invalid subaccount selected.'
                        ]
                    ], 404);
                }
                return redirect()->back()->withErrors(['error' => 'Invalid subaccount selected.']);
            }
            // Validate subaccount ownership
            if ($subaccount->user_id !== Auth::id()) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'data' => [
                            'status' => 'error',
                            'message' => 'You do not have permission to use this subaccount.'
                        ]
                    ], 403);
                }
                return redirect()->back()->withErrors(['subaccount_id' => 'You do not have permission to use this subaccount.']);
            }
        // dd($request->all());

            $accountNumber = $subaccount->account_number;
            $accountName = $subaccount->account_name;
            $bankName = $subaccount->bank_name;

        $imagePath = null;
        if ($request->hasFile('cover_image')) {
            $imagePath = $request->file('cover_image')->store('subscriptions', 'public');
        }

        // ✅ Generate unique payment reference
        do {
            $reference = 'SUB-' . now()->format('Ymd') . '-' . Str::upper(Str::random(8));
        } while (\App\Models\Subscription::where('payment_reference', $reference)->exists());

        // ✅ Create subscription
        $subscription = Subscription::create([
            'user_id' => $request->user()?->id ?? auth()->id(),
            'title' => $request->title,
            'cover_image' => $imagePath,
            'subscription_interval' => $request->subscription_interval,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'visibility' => $request->visibility,
            'payment_reference' => $reference, // ✅ Add here
            'subaccount_id' => $request->subaccount_id,
            'subaccount' => $bankName,
            'subaccount_name' => $accountName,
            'subaccount_number' => $accountNumber,
            'percentage' => $request->percentage ?? 10, 
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Subscription created successfully',
                'data' => $subscription
            ], 201);
        }

        return redirect()->route('add_subscription.create')
            ->with('success', 'Subscription created successfully.');
    }

    public function edit($id)
    {
        $user = auth()->user();
        $team = TeamMembers::where('user_id', $user->id)->first();
        $ownerId = $team ? $team->owner_id : $user->id;
        $countries = Countries::all();
        $subscription = Subscription::findOrFail($id);
        $subaccounts = Subaccount::where('user_id', $user->id)->get();

            // Fetch subscription records (subscribers) for this subscription
    $subscribers = SubscriptionRecord::where('subscription_id', $subscription->id)
                        ->orderBy('created_at', 'desc')
                        ->get();

    
    return view('business.edit_subscription', compact('countries', 'subscription','subaccounts', 'subscribers'));
    }
    


public function update(Request $request, $id)
{
        // dd($request->all());

    $user = Auth::user();
    $data = $request->all();

    // ✅ Validate input
    $validator = Validator::make($data, [
        'title' => 'sometimes|string|max:255',
        'subscription_interval' => 'sometimes|string|in:daily,weekly,monthly,yearly',
        'amount' => 'sometimes|numeric|min:0',
        'currency' => 'sometimes|string|max:10',
        'visibility' => 'sometimes|string|in:public,private',
        'subaccount_id' => 'required|integer|exists:subaccounts,id',
        'percentage' => 'sometimes|numeric|min:0|max:100',
        'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    if ($validator->fails()) {
        if ($request->expectsJson()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        return back()->withErrors($validator);
    }

    // ✅ Get subscription
    $subscription = Subscription::where('id', $id)
        ->where('user_id', $user->id)
        ->first();

    if (!$subscription) {
        $errorMessage = 'Subscription not found or you do not have permission to edit it.';
        if ($request->expectsJson()) {
            return response()->json(['data' => ['status' => 'error', 'message' => $errorMessage]], 404);
        }
        return redirect()->back()->with('error', $errorMessage);
    }

    // ✅ Get subaccount details and validate ownership
    $subaccount = Subaccount::find($request->subaccount_id);

    if (!$subaccount) {
        $errorMessage = 'Invalid subaccount selected.';
        if ($request->expectsJson()) {
            return response()->json(['data' => ['status' => 'error', 'message' => $errorMessage]], 404);
        }
        return back()->withErrors(['subaccount_id' => $errorMessage]);
    }

    if ($subaccount->user_id !== $user->id) {
        $errorMessage = 'You do not have permission to use this subaccount.';
        if ($request->expectsJson()) {
            return response()->json(['data' => ['status' => 'error', 'message' => $errorMessage]], 403);
        }
        return back()->withErrors(['subaccount_id' => $errorMessage]);
    }

    // ✅ Handle image upload (if provided)
    if ($request->hasFile('cover_image')) {
        $path = $request->file('cover_image')->store('subscriptions', 'public');
        $subscription->cover_image = $path;
    }

    // ✅ Update fields safely (only if provided)
    $subscription->title = $request->title ?? $subscription->title;
    $subscription->subscription_interval = $request->subscription_interval ?? $subscription->subscription_interval;
    $subscription->amount = $request->amount ?? $subscription->amount;
    $subscription->currency = $request->currency ?? $subscription->currency;
    $subscription->visibility = $request->visibility ?? $subscription->visibility;
    $subscription->percentage = $request->percentage ?? $subscription->percentage;

    // ✅ Update subaccount details
    $subscription->subaccount_id = $subaccount->id;
    $subscription->subaccount = $subaccount->bank_name;
    $subscription->subaccount_name = $subaccount->account_name;
    $subscription->subaccount_number = $subaccount->account_number;

    $subscription->save();

    // ✅ Unified Response
    if ($request->expectsJson()) {
        return response()->json([
            'message' => 'Subscription updated successfully',
            'data' => $subscription
        ], 200);
    }

    return redirect()->back()->with('success', 'Subscription updated successfully');
}




    public function destroy(Request $request, $id)
    {
        $subscription = Subscription::where('user_id', $request->user()?->id ?? auth()->id())->findOrFail($id);

        $subscription->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Subscription deleted'], 200);
        }

        return redirect()->route('subscriptions.index')->with('success', 'Subscription deleted.');
    }



    
    public function subscriptioncheckout(Request $request, $id)
    {
        $subscription = Subscription::where("payment_reference", $id)->first();
        if (!$subscription) {
            if (request()->expectsJson()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'An error occured or subscription not found.'
                    ]
                ], 404);
            }
        }

        if (request()->expectsJson()) {
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Successfully retrieved subscription.',
                    'subscription' => $subscription
                ]
            ], 200);
        }
        //return view
        return view("business.subscriptionCheckout", compact("subscription"));
    }
    public function storeSubscriptionRecord(Request $request)
    {
        $validated = $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
        ]);

        $userId = auth()->id();

        // ✅ Get the related subscription to determine interval
        $subscription = Subscription::find($validated['subscription_id']);
        if (!$subscription) {
            return response()->json([
                'message' => 'Subscription not found.'
            ], 404);
        }

        // ✅ Determine start_date and end_date based on interval
        $startDate = now();

        switch ($subscription->subscription_interval) {
            case 'daily':
                $endDate = $startDate->copy()->addDay();
                break;
            case 'weekly':
                $endDate = $startDate->copy()->addWeek();
                break;
            case 'monthly':
                $endDate = $startDate->copy()->addMonth();
                break;
            case 'yearly':
                $endDate = $startDate->copy()->addYear();
                break;
            default:
                $endDate = $startDate->copy(); // fallback
        }

        // ✅ Determine if expired
        $isExpired = $endDate->isPast();

        // ✅ Create record
        $record = SubscriptionRecord::create([
            'user_id' => $userId,
            'subscription_id' => $validated['subscription_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'amount' => $validated['amount'],
            'currency' => $validated['currency'],
            'status' => 'pending',
            'reference' => now()->format('YmdHis') . '_' . Str::uuid(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_expired' => $isExpired,
        ]);

        return response()->json([
            'message' => 'Subscription record created successfully.',
            'data' => $record,
        ], 201);
    }



    public function exportSubscribers($id)
    {
            $subscription = Subscription::find($id);
        if (!$subscription) {
            return response()->json([
                'status' => 'error',
                'message' => 'Subscription not found'
            ], 404);
        }

            // Fetch records
            $records = SubscriptionRecord::where('subscription_id', $subscription->id)->get([
                'name', 'email', 'phone', 'amount', 'currency', 'status', 'reference', 'user_id', 'subscription_id', 'start_date', 'end_date', 'is_expired'
            ]);

            $filename = 'subscribers_' . now()->format('Ymd_His') . '.csv';

            // Prepare CSV as a callback
            $callback = function() use ($records) {
                $file = fopen('php://output', 'w');

                // Header
                fputcsv($file, ['Name', 'Email', 'Phone', 'Amount', 'Currency', 'Status', 'Reference', 'User ID', 'Subscription ID', 'Start Date', 'End Date', 'Is Expired']);

                // Rows
                foreach ($records as $record) {
                    fputcsv($file, [
                        $record->name,
                        $record->email,
                        $record->phone,
                        $record->amount,
                        $record->currency,
                        $record->status,
                        $record->reference,
                        $record->user_id,
                        $record->subscription_id,
                        $record->start_date,
                        $record->end_date,
                        $record->is_expired ? 'Yes' : 'No',
                    ]);
                }

                fclose($file);
            };

            // Web: normal CSV download
            if (!request()->expectsJson()) {
                return Response::stream($callback, 200, [
                    "Content-Type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=\"$filename\"",
                ]);
            }

            // API: return CSV as base64 string in JSON
            ob_start();
            $callback();
            $csvContent = ob_get_clean();
            $csvBase64 = base64_encode($csvContent);

            return response()->json([
                'status' => 'success',
                'filename' => $filename,
                'csv_base64' => $csvBase64,
                'message' => 'CSV exported successfully'
            ]);
    }

    public function exportSubscriberss($id)
    {
        $user = Auth::user();

        $subscription = Subscription::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$subscription) {
            return response()->json([
                'status' => 'error',
                'message' => 'Subscription not found or not owned by you.'
            ], 404);
        }

        $fileName = 'subscription_records_' . $subscription->id . '.xlsx';
        $export = new SubscriptionRecordsExport($subscription->id);

        if (request()->expectsJson()) {
            // ✅ Generate the Excel file content as raw string
            $excelContent = Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);

            return response()->json([
                'status' => 'success',
                'filename' => $fileName,
                'excel_base64' => base64_encode($excelContent),
                'message' => 'Excel exported successfully.'
            ]);
        }

        // Web download
        return Excel::download($export, $fileName);
    }



public function subscriptionDetails(Request $request, $subscription_id)
{
    $user = auth()->user();
    $team = TeamMembers::where('user_id', $user->id)->first();
    $ownerId = $team ? $team->owner_id : $user->id;

    // Make sure the subscription belongs to this user
    $subscription = Subscription::where('id', $subscription_id)
        ->where('user_id', $ownerId)
        ->first();

    if (!$subscription) {
        return response()->json([
            'status' => 'error',
            'message' => 'Subscription not found or not owned by you.'
        ], 404);
    }

    // Query subscription records
    $query = SubscriptionRecord::where('subscription_id', $subscription_id)
        ->where('user_id', $user->id);

    // Optional filters
    if ($request->has('start_date')) {
        $query->whereDate('start_date', '>=', $request->start_date);
    }
    if ($request->has('end_date')) {
        $query->whereDate('end_date', '<=', $request->end_date);
    }
    if ($request->has('is_expired')) {
        $query->where('is_expired', $request->is_expired);
    }

    $records = $query->get();

    // Stats
    $activeSubscribers = $records->where('is_expired', false)->count();
    $unsubscribers = $records->where('is_expired', true)->count();
    $totalSubscribers = $records->count();

    return response()->json([
        'status' => 'success',
        'subscription' => [
            'id' => $subscription->id,
            'name' => $subscription->name,
        ],
        'stats' => [
            'active_subscribers' => $activeSubscribers,
            'unsubscribers' => $unsubscribers,
            'total_subscribers' => $totalSubscribers,
        ],
        'subscribers' => $records
    ]);
}





}
