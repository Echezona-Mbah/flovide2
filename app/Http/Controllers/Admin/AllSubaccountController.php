<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subaccount;
use Illuminate\Http\Request;

class AllSubaccountController extends Controller
{
         public function index(Request $request)
{
    $query = Subaccount::query();

    // Live search filter
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;

        $query->where(function($q) use ($search) {
            $q->where('account_name', 'LIKE', "%$search%")
              ->orWhere('bank', 'LIKE', "%$search%")
              ->orWhere('account_number', 'LIKE', "%$search%")
              ->orWhere('country', 'LIKE', "%$search%")
              ->orWhere('currency', 'LIKE', "%$search%")
              ->orWhere('alias', 'LIKE', "%$search%");
        });
    }

    $allsubaccount = $query->orderBy('created_at', 'desc')->paginate(10);

    return view('admin.subaccount', compact('allsubaccount'));
}
}
