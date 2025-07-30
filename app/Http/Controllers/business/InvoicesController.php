<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Invoices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

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

    private function generateTrackingCode(): string
    {
        do {
            $code = 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(10));
        } while (Invoices::where('tracking_code', $code)->exists());

        return $code;
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
            'status' => 'required|in:draft,pending',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.rate_enabled' => 'required|boolean',
            'items.*.rate' => 'nullable|numeric',
            'items.*.total' => 'required|numeric',
        ]);

        // Generate a unique tracking code
        $trackingCode = $this->generateTrackingCode();

        $invoice = Invoices::create([
            'user_id' => Auth::id(),
            'invoice_number' => $validated['invoice_number'],
            'tracking_code' => $trackingCode,
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
        //check if the user owns the invoice
        $invoice = Invoices::findOrFail($id);
        if ($invoice->user_id !== Auth::id()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            abort(403, 'Unauthorized');
        }
        
        $invoice = Invoices::with('items')->findOrFail($id); 

        $validated = $request->validate([
            'invoice_number' => 'sometimes|string',
            'billed_to' => 'sometimes|string',
            'due_date' => 'sometimes|date',
            'address' => 'sometimes|string|nullable',
            'currency' => 'sometimes|string',
            'note' => 'sometimes|string|nullable',
            'status' => 'sometimes|in:pending,draft',
            'items' => 'sometimes|array|min:1',
            'items.*.id' => 'nullable|integer',
            'items.*.item_name' => 'required|string',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.rate_enabled' => 'required|boolean',
            'items.*.rate' => 'nullable|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
        ]);

        // Track changes
        $original = $invoice->toArray();
        $changes = [];

        $fieldsToCompare = ['invoice_number', 'billed_to', 'due_date', 'address', 'currency', 'note', 'status'];

        foreach ($fieldsToCompare as $field) {
            if (isset($validated[$field]) && $validated[$field] != $invoice->$field) {
                $changes[$field] = [
                    'old' => $invoice->$field,
                    'new' => $validated[$field]
                ];
                $invoice->$field = $validated[$field];
            }
        }

        $invoice->save();

        // Handle items update
        if (isset($validated['items'])) {
            // Track changes of items being updated
            $originalItems = $invoice->items->keyBy('id');

            // Loop through each item in the request
            foreach ($validated['items'] as $itemData) {
                if (!empty($itemData['id']) && isset($originalItems[$itemData['id']])) {
                    $item = $originalItems[$itemData['id']];
                    $itemChanged = false;

                    foreach (['item_name', 'qty', 'rate_enabled', 'rate', 'total'] as $field) {
                        if ($item->$field != $itemData[$field]) {
                            $itemChanged = true;
                            $changes["item_{$item->id}_$field"] = [
                                'old' => $item->$field,
                                'new' => $itemData[$field]
                            ];
                            $item->$field = $itemData[$field];
                        }
                    }

                    if ($itemChanged) {
                        $item->save();
                    }
                } else {
                    // New item
                    $invoice->items()->create($itemData);
                    $changes['new_item'][] = $itemData;
                }
            }

            //detect deleted items
            $updatedIds = collect($validated['items'])->pluck('id')->filter()->all();
            $deletedItems = $invoice->items()->whereNotIn('id', $updatedIds)->get();

            foreach ($deletedItems as $deletedItem) {
                $changes['deleted_item'][] = $deletedItem->toArray();
                $deletedItem->delete();
            }
        }

        return response()->json([
            'message' => 'Invoice updated successfully',
            'data' => $invoice->fresh('items'),
            'changes' => $changes
        ]);
    }


    public function edit(Request $request, $id)
    {
        $invoice = Invoices::with('items')->findOrFail($id);

        //check if the user owns the invoice
        if ($invoice->user_id !== Auth::id()) {
            if ($request->expectsJson()) {
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
