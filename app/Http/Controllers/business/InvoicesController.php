<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoices;
use Illuminate\Support\Facades\Auth;

class InvoicesController extends Controller
{
    //
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($request->expectsJson()) {
            $invoices = Invoices::with('client')->where('user_id', $user->id)->latest()->paginate(10);
            return response()->json($invoices);
        } else {
            $invoices = Invoices::with('client')->where('user_id', $user->id)->latest()->paginate(10);
            // return view('invoices.index', compact('invoices'));
        }
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|unique:invoices',
            'customer_id' => 'required|exists:customer,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid,overdue,cancelled'
        ]);

        $invoice = Invoices::create($validated);
        return response()->json(['message' => 'Invoice created', 'data' => $invoice], 201);
    }

    public function show($id)
    {
        $invoice = Invoices::with('client')->findOrFail($id);
        return response()->json($invoice);
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoices::findOrFail($id);

        $validated = $request->validate([
            'amount' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:pending,paid,overdue,cancelled'
        ]);

        $invoice->update($validated);
        return response()->json(['message' => 'Invoice updated', 'data' => $invoice]);
    }

    public function destroy($id)
    {
        $invoice = Invoices::findOrFail($id);
        $invoice->delete();
        return response()->json(['message' => 'Invoice deleted']);
    }
}
