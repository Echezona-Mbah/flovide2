<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminBroadcastEmail;
use App\Models\User;
use App\Models\Personal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class BroadcastController extends Controller
{
    //
    public function broadcastIndex(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $search = $request->input('search');
        $status = $request->input('status');

        $query = AdminBroadcastEmail::where(function($q) use ($user) {
            $q->where('user_id', $user->id)
            ->orWhere('recipient_email', $user->email);
        });

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'LIKE', "%{$search}%")
                ->orWhere('message', 'LIKE', "%{$search}%")
                ->orWhere('recipient_email', 'LIKE', "%{$search}%");
            });
        }

        if ($status && in_array($status, ['sent', 'pending', 'failed'])) {
            $query->where('status', $status);
        }

        $broadcasts = $query->latest('id')->paginate(15)->appends($request->query());

        $totalCount = AdminBroadcastEmail::where(function($q) use ($user) {
            $q->where('user_id', $user->id)->orWhere('recipient_email', $user->email);
        })->count();

        $sentCount = AdminBroadcastEmail::where(function($q) use ($user) {
            $q->where('user_id', $user->id)->orWhere('recipient_email', $user->email);
        })->where('status', 'sent')->count();

        $failedCount = AdminBroadcastEmail::where(function($q) use ($user) {
            $q->where('user_id', $user->id)->orWhere('recipient_email', $user->email);
        })->where('status', 'failed')->count();

        $pendingCount = AdminBroadcastEmail::where(function($q) use ($user) {
            $q->where('user_id', $user->id)->orWhere('recipient_email', $user->email);
        })->where('status', 'pending')->count();

        return view('admin.broadcast_user', compact(
            'user', 
            'broadcasts', 
            'search', 
            'status', 
            'totalCount', 
            'sentCount', 
            'failedCount', 
            'pendingCount'
        ));
    }


    public function broadcastAllIndex(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = AdminBroadcastEmail::with('user');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'LIKE', "%{$search}%")
                ->orWhere('message', 'LIKE', "%{$search}%")
                ->orWhere('recipient_email', 'LIKE', "%{$search}%")
                ->orWhereHas('user', function($userQ) use ($search) {
                    $userQ->where('firstname', 'LIKE', "%{$search}%")
                          ->orWhere('lastname', 'LIKE', "%{$search}%")
                          ->orWhere('business_name', 'LIKE', "%{$search}%")
                          ->orWhere('email', 'LIKE', "%{$search}%");
                });
            });
        }

        if ($status && in_array($status, ['sent', 'pending', 'failed'])) {
            $query->where('status', $status);
        }

        $broadcasts = $query->latest('id')->paginate(15)->appends($request->query());

        $totalCount = AdminBroadcastEmail::count();
        $sentCount = AdminBroadcastEmail::where('status', 'sent')->count();
        $failedCount = AdminBroadcastEmail::where('status', 'failed')->count();
        $pendingCount = AdminBroadcastEmail::where('status', 'pending')->count();

        return view('admin.broadcast_all_users', compact(
            'broadcasts', 
            'search', 
            'status', 
            'totalCount', 
            'sentCount', 
            'failedCount', 
            'pendingCount'
        ));
    }


    public function broadcastPersonalIndex(Request $request, $id)
    {
        $user = Personal::findOrFail($id);

        $search = $request->input('search');
        $status = $request->input('status');

        // Only broadcasts belonging to this Personal
        $query = AdminBroadcastEmail::where('personal_id', $user->id);

        // Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'LIKE', "%{$search}%")
                ->orWhere('message', 'LIKE', "%{$search}%")
                ->orWhere('recipient_email', 'LIKE', "%{$search}%");
            });
        }

        // Status filter
        if ($status && in_array($status, ['sent', 'pending', 'failed'])) {
            $query->where('status', $status);
        }

        // Paginated broadcasts
        $broadcasts = $query
            ->latest('id')
            ->paginate(15)
            ->appends($request->query());

        // Base count query for this Personal
        $countQuery = AdminBroadcastEmail::where('personal_id', $user->id);

        $totalCount = (clone $countQuery)->count();

        $sentCount = (clone $countQuery)
            ->where('status', 'sent')
            ->count();

        $failedCount = (clone $countQuery)
            ->where('status', 'failed')
            ->count();

        $pendingCount = (clone $countQuery)
            ->where('status', 'pending')
            ->count();

        return view('admin.broadcast_personal', compact(
            'user',
            'broadcasts',
            'search',
            'status',
            'totalCount',
            'sentCount',
            'failedCount',
            'pendingCount'
        ));
    }

    public function broadcastAllPersonalIndex(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        // Only get broadcasts belonging to Personal accounts
        $query = AdminBroadcastEmail::with('personal')
            ->whereNotNull('personal_id');

        // Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'LIKE', "%{$search}%")
                ->orWhere('message', 'LIKE', "%{$search}%")
                ->orWhere('recipient_email', 'LIKE', "%{$search}%")
                ->orWhereHas('personal', function ($personalQuery) use ($search) {
                    $personalQuery->where('firstname', 'LIKE', "%{$search}%")
                        ->orWhere('lastname', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('person_phone', 'LIKE', "%{$search}%");
                });
            });
        }

        // Filter by status
        if ($status && in_array($status, ['sent', 'pending', 'failed'])) {
            $query->where('status', $status);
        }

        // Paginated broadcasts
        $broadcasts = $query
            ->latest('id')
            ->paginate(15)
            ->appends($request->query());

        // Counts - ONLY personal broadcasts
        $countQuery = AdminBroadcastEmail::whereNotNull('personal_id');

        $totalCount = (clone $countQuery)->count();

        $sentCount = (clone $countQuery)
            ->where('status', 'sent')
            ->count();

        $failedCount = (clone $countQuery)
            ->where('status', 'failed')
            ->count();

        $pendingCount = (clone $countQuery)
            ->where('status', 'pending')
            ->count();

        return view('admin.broadcast_all_personal', compact(
            'broadcasts',
            'search',
            'status',
            'totalCount',
            'sentCount',
            'failedCount',
            'pendingCount'
        ));
    }
}
