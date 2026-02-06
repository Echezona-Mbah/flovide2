<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class AllSubscriptionController extends Controller
{
               public function index()
    {
        $subscriptions = Subscription::latest()->paginate(10);
        return view('admin.subscriptions', compact('subscriptions'));
    }

    public function show($id)
    {
        $subscription = Subscription::with('subscription')->findOrFail($id);
        $records = $subscription->subscription()->paginate(10);

        return view('admin.subscriptionrecord', compact('subscription', 'records'));
    }
}
