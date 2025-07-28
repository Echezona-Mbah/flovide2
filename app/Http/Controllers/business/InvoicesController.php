<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;

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
            $invoices = Invoices::with('user')->where('user_id', $user->id)->latest()->paginate(10);
            return response()->json($invoices);
        } else {
            $invoices = Invoices::with('user')->where('user_id', $user->id)->latest()->paginate(10);
            return view('business.invoicesList', compact('invoices'));
        }
    }

    public function create()
    {
        // return create page view
        return view('business.createInvoice');
    }



    public function store(Request $request)
    {
        //    dd($request->all());
        // Validate the request
        $validated = $request->validate([
            'invoice_number' => 'required|unique:invoices',
            'billed_to' => 'required|string',
            'due_date' => 'required|date',
            'address' => 'nullable|string',
            'currency' => 'required|string|max:10',
            'note' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:draft,sent',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.rate_enabled' => 'required|boolean',
            'items.*.rate' => 'nullable|numeric',
            'items.*.total' => 'required|numeric',
        ]);

        $invoice = Invoices::create([
            'user_id' => Auth::id(),
            'invoice_number' => $validated['invoice_number'],
            'billed_to' => $validated['billed_to'],
            'address' => $validated['address'] ?? null,
            'due_date' => $validated['due_date'],
            'currency' => $validated['currency'],
            'note' => $validated['note'] ?? null,
            'amount' => $validated['amount'],
            'status' => $validated['status'],
        ]);

        foreach ($validated['items'] as $item) {
            $invoice->items()->create([
                'item_name' => $item['item_name'],
                'qty' => $item['qty'],
                'rate' => $item['rate_enabled'] ? $item['rate'] : null,
                'total' => $item['total'],
                'rate_enabled' => $item['rate_enabled'],
            ]);
        }

        return response()->json(['status' => 'success', 'invoice_id' => $invoice->id]);
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

    public function edit(Request $request, $id)
    {
        $invoice = Invoices::with('items')->findOrFail($id);

        //check if the user owns the invoice
        if ($invoice->user_id !== Auth::id()) {
            if ($request()->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            abort(403, 'Unauthorized');
        }
        // If it's an API request, return JSON data
        if (request()->expectsJson()) {
            return response()->json($invoice);
        }
        // Otherwise, return the edit view
        return view('business.editInvoice', compact('invoice'));
    }


    public function destroy(Request $request, $id)
    {
        $invoice = Invoices::findOrFail($id);

        if ($invoice->user_id !== Auth::id()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            abort(403, 'Unauthorized');
        }

        $invoice->delete(); // Soft delete

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Invoice deleted successfully.']);
        }

        return redirect()->back()->with('success', 'Invoice deleted successfully.');
    }
}
