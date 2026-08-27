<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beneficia;
use Illuminate\Http\Request;

class AllBeneficiasController extends Controller
{
// public function index(Request $request)
// {
//     $query = Beneficia::query();

//     // Live search filter
//     if ($request->has('search') && $request->search != '') {
//         $search = $request->search;

//         $query->where(function($q) use ($search) {
//             $q->where('account_name', 'LIKE', "%$search%")
//               ->orWhere('bank', 'LIKE', "%$search%")
//               ->orWhere('account_number', 'LIKE', "%$search%")
//               ->orWhere('country', 'LIKE', "%$search%")
//               ->orWhere('currency', 'LIKE', "%$search%")
//               ->orWhere('alias', 'LIKE', "%$search%");
//         });
//     }

//     $allbeneficia = $query->orderBy('created_at', 'desc')->paginate(10);

//     return view('admin.beneficias', compact('allbeneficia'));
// }


  public function index(Request $request)
    {
        $search = $request->search;

        $allowedPerPage = [25, 50, 100, 250, 500];
        $perPage = (int) $request->input('per_page', 25);

        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 25;
        }

        $query = Beneficia::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('account_name', 'LIKE', "%{$search}%")
                  ->orWhere('bank', 'LIKE', "%{$search}%")
                  ->orWhere('account_number', 'LIKE', "%{$search}%")
                  ->orWhere('country', 'LIKE', "%{$search}%")
                  ->orWhere('currency', 'LIKE', "%{$search}%")
                  ->orWhere('alias', 'LIKE', "%{$search}%");
            });
        }

        $allbeneficia = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.beneficias', compact('allbeneficia', 'search', 'perPage', 'allowedPerPage'));
    }
}
