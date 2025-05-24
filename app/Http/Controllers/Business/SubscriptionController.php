<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'subscription_interval' => 'required|in:daily,weekly,monthly,yearly',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
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

        $imagePath = null;
        if ($request->hasFile('cover_image')) {
            $imagePath = $request->file('cover_image')->store('subscriptions', 'public');
        }

        $subscription = Subscription::create([
            'user_id' => $request->user()?->id ?? auth()->id(),
            'title' => $request->title,
            'cover_image' => $imagePath,
            'subscription_interval' => $request->subscription_interval,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'visibility' => $request->visibility,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Subscription created successfully',
                'data' => $subscription
            ], 201);
        }

        return redirect()->route('subscriptions.create')->with('success', 'Subscription created successfully.');
    }


    public function index(Request $request)
    {
        $subscriptions = Subscription::where('user_id', $request->user()?->id ?? auth()->id())->get();

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $subscriptions
            ], 200);
        }

        return view('subscriptions.index', compact('subscriptions')); // Optional Blade view
    }


    public function update(Request $request, $id)
    {
        $subscription = Subscription::where('user_id', $request->user()?->id ?? auth()->id())->findOrFail($id);

        $rules = [
            'title' => 'sometimes|required|string|max:255',
            'subscription_interval' => 'sometimes|required|in:daily,weekly,monthly,yearly',
            'amount' => 'sometimes|required|numeric|min:0',
            'currency' => 'sometimes|required|string|max:10',
            'visibility' => 'sometimes|required|in:public,private',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('subscriptions', 'public');
            $subscription->cover_image = $path;
        }

        $subscription->fill($request->only([
            'title', 'subscription_interval', 'amount', 'currency', 'visibility'
        ]));

        $subscription->save();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Subscription updated', 'data' => $subscription], 200);
        }

        return redirect()->route('subscriptions.index')->with('success', 'Subscription updated successfully.');
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





}
