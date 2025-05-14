<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddCustomerController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $customers = Customer::where('user_id', $user->id)->get();
        dd($customers);die();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'customers records retrieved successfully',
                'data' => $customers,
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ], 200);
        }
        return view('business.customer', compact('customers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'bank' => 'nullable|string',
            'country_id' => 'required|exists:countries,id',
            'account_number' => 'required|string',
            'account_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ], 422);
        }

        $customer = Customer::create([
            ...$request->all(),
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'message' => 'Customer created successfully',
            'data' => $customer,
            'method' => $request->method(),
            'url' => $request->fullUrl()
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        if ($customer->user_id != auth()->id()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized to edit this customer',
                ], 403);
            } else {
                return redirect()->back()->withErrors(['message' => 'Unauthorized to edit this customer']);
            }
        }

        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'bank' => 'nullable|string',
            'country_id' => 'required|exists:countries,id',
            'account_number' => 'required|string',
            'account_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ], 422);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }

        $customer->update($request->all());
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Customer updated successfully',
                'data' => $customer,
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ]);
        }
        return redirect()->route('customers.index')->with('status', 'Customer updated successfully');
    }

    public function destroy(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        if ($customer->user_id != auth()->id()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized to delete this customer',
                ], 403);
            } else {
                return redirect()->back()->withErrors(['message' => 'Unauthorized to delete this customer']);
            }
        }
        $customer->delete();
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Customer deleted successfully',
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ]);
        }
        return redirect()->route('customers.index')->with('status', 'Customer deleted successfully');
    }
}
