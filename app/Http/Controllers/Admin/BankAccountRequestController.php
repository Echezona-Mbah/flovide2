<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function update(Request $request, $id)
    {
        $bankAccountRequest = PersonalBankAccountRequest::with('personal')->findOrFail($id);

        // Prevent updating an already confirmed request
        if ($bankAccountRequest->status === 'confirmed') {
            return redirect()
                ->back()
                ->with('error', 'This bank account request has already been confirmed.');
        }

        // Make sure the request has a linked personal account
        if (!$bankAccountRequest->personal) {
            Log::error('Bank account request has no linked personal account', [
                'request_id' => $bankAccountRequest->id,
                'admin_id' => Auth::guard('admin')->id(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Unable to update request. The linked personal account was not found.');
        }

        DB::transaction(function () use ($bankAccountRequest) {

            $bankAccountRequest->personal->update([
                'nin' => $bankAccountRequest->nin,
                'bvn' => $bankAccountRequest->bvn,
                'nin_status' => 'under review',
                'bvn_status' => 'under review',
            ]);

            $bankAccountRequest->update([
                'status' => 'under review',
                'processed_at' => now(),
            ]);

        });

        Log::info('Bank account request updated to under review', [
            'request_id' => $id,
            'admin' => Auth::guard('admin')->id(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Request updated and statuses set to under review.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $bankAccountRequest = PersonalBankAccountRequest::with('personal')->findOrFail($id);

        // Prevent changing an already confirmed request
        if ($bankAccountRequest->status === 'confirmed') {
            return redirect()
                ->back()
                ->with('error', 'A confirmed bank account request cannot be rejected.');
        }

        // Prevent rejecting an already rejected request
        if ($bankAccountRequest->status === 'rejected') {
            return redirect()
                ->back()
                ->with('error', 'This bank account request has already been rejected.');
        }

        DB::transaction(function () use ($bankAccountRequest, $request) {
            $bankAccountRequest->update([
                'status' => 'rejected',
                'admin_note' => $request->input('admin_note'),
                'processed_at' => now(),
            ]);
        });

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
