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

        return view('admin.bank_account_requests', compact('bankAccountRequests', 'search'));
    }

    public function show($id) {
        $bankAccountRequest = PersonalBankAccountRequest::with('personal')
            ->findOrFail($id);

        return view('admin.bank_account_request_show', compact('bankAccountRequest'));
    }
    
}
