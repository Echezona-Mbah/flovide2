<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Remita;
use App\Models\RemitaPayment;
use Illuminate\Http\Request;

class AllRemitaController extends Controller
{
            public function index()
    {
        $remitas = Remita::latest()->paginate(10);
        return view('admin.remitas', compact('remitas'));
    }

    public function show($id)
    {
        $remita = Remita::with('payments')->findOrFail($id);
        $records = $remita->payments()->paginate(10);

        return view('admin.remitarecord', compact('remita', 'records'));
    }
}
