<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ProviderErrorController extends Controller
{
    public function index(Request $request)
    {
        $storageAvailable = Schema::hasTable('admin_notifications');
        $providerErrors = collect();
        $counts = ['total' => 0, 'open' => 0, 'resolved' => 0];

        if ($storageAvailable) {
            $providerErrors = AdminNotification::query()->where('type', 'provider_error');
            $counts = [
                'total' => (clone $providerErrors)->count(),
                'open' => (clone $providerErrors)->whereNull('read_at')->count(),
                'resolved' => (clone $providerErrors)->whereNotNull('read_at')->count(),
            ];

            $status = $request->input('status', 'open');
            $providerErrors = (clone $providerErrors)
                ->when($status === 'open', fn ($query) => $query->whereNull('read_at'))
                ->when($status === 'resolved', fn ($query) => $query->whereNotNull('read_at'))
                ->when($request->filled('search'), function ($query) use ($request) {
                    $search = $request->string('search')->toString();
                    $query->where(function ($searchQuery) use ($search) {
                        $searchQuery->where('title', 'like', "%{$search}%")
                            ->orWhere('body', 'like', "%{$search}%");
                    });
                })
                ->latest()
                ->paginate(15)
                ->withQueryString();
        }

        return view('admin.provider_errors', compact('providerErrors', 'counts', 'storageAvailable'));
    }

    public function resolve($id)
    {
        $notification = AdminNotification::query()
            ->where('type', 'provider_error')
            ->findOrFail($id);

        $notification->markResolved();

        return back()->with('success', 'Provider error marked as resolved.');
    }
}
