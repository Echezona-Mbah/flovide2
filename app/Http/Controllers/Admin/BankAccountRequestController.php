<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\PersonalBankAccountRequest;

class BankAccountRequestController extends Controller
{
    public function index(Request $request) {
        $search = $request->input('search');

        $bankAccountRequests = PersonalBankAccountRequest::with('personal')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('personal', function ($q) use ($search) {
                    $q->where('firstname', 'like', "%{$search}%")
                      ->orWhere('lastname', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $totalRequests = PersonalBankAccountRequest::count();
        $pendingRequests = PersonalBankAccountRequest::whereIn('status', ['pending', 'processing'])->count();
        $approvedRequests = PersonalBankAccountRequest::where('status', ['confirmed', 'approved'])->count();
        $rejectedRequests = PersonalBankAccountRequest::where('status', 'rejected')->count();

        return view('admin.bank_account_requests', compact(
            'bankAccountRequests',
            'search',
            'totalRequests',
            'pendingRequests',
            'approvedRequests',
            'rejectedRequests'
        ));
    }

    public function show($id) {
        $bankAccountRequest = PersonalBankAccountRequest::with('personal')
            ->findOrFail($id);

        return view('admin.bank_account_request_show', compact('bankAccountRequest'));
    }

    public function reject(Request $request, $id)
    {
        $bankAccountRequest = PersonalBankAccountRequest::findOrFail($id);

        $bankAccountRequest->update([
            'status' => 'rejected',
            'admin_note' => $request->input('admin_note'),
            'processed_at' => now(),
        ]);

        Log::info('Bank account request rejected', [
            'request_id' => $id,
            'admin' => Auth::guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.bank-account-requests')
            ->with('success', 'Bank account request has been rejected.');
    }

    public function destroy($id)
    {
        $bankAccountRequest = PersonalBankAccountRequest::findOrFail($id);
        $bankAccountRequest->delete(); // soft delete

        Log::info('Bank account request soft-deleted', [
            'request_id' => $id,
            'admin' => Auth::guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.bank-account-requests')
            ->with('success', 'Bank account request has been deleted.');
    }
}
