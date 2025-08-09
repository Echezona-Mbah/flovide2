<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Countries;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class AddCustomerController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $customers = Customer::where('user_id', $user->id)->paginate(5);
      

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


    public function show($id)
    {
        $customer = Customer::with('country')->find($id); 
    
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }
    
        return response()->json([
            'data' => [
                'name' => $customer->customer_name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'created_at' => $customer->created_at,
                'bank' => $customer->bank,
                'account_name' => $customer->account_name,
                'bank_country' => optional($customer->country)->name, // returns country name or null
                'account_number' => $customer->account_number,
            ]
        ]);
    }
    public function create() {
        $user = auth()->user();
        $countries = Countries::all();
        $banks = Bank::all();
        $customers = Customer::where('user_id', $user->id)
        ->paginate(5);
        return view('business.add_customer',compact('countries', 'banks','customers'));
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
    
        // Create the customer
        $customer = Customer::create([
            'customer_name' => $request->customer_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'bank' => $request->bank,
            'country_id' => $request->country_id,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'user_id' => $request->user()?->id ?? auth()->id(),
        ]);
    
        // Return based on request type
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Customer created successfully',
                'data' => $customer,
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ], 201);
        }
    
        return redirect()->route('customer')->with('success', 'Customer created successfully');
    }
    
    public function json($id)
    {
        $customer = Customer::findOrFail($id);
        return response()->json($customer);
    }
    public function edit($id)
    {
        $user = auth()->user();
        $customer = Customer::findOrFail($id);
        $countries = Countries::all();
        $banks = Bank::all();
        $customeres = Customer::where('user_id', $user->id)
        ->paginate(5);
        return view('business.edit_customer', compact('customer', 'countries', 'banks','customeres'));
    }

    public function update(Request $request, $id)
    {
         //dd($request->all());
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
        return redirect()->route('customer')->with('success', 'Customer updated successfully');
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
        return redirect()->route('customer')->with('status', 'Customer deleted successfully');
    }


    public function search(Request $request)
    {
        $query = $request->query('query');
    
        $customers = Customer::where('customer_name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->get();
    
        return response()->json($customers);
    }

    public function exportCsv()
    {
        $customers = Customer::all(); // ✅ Not ->find($id)

    
        $filename = "customers.csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
    
        $columns = ['Customer Name', 'Email', 'Phone', 'Date Added'];
    
        $callback = function () use ($customers, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
    
            foreach ($customers as $customer) {
                fputcsv($file, [
                    $customer->customer_name,
                    $customer->email,
                    $customer->phone,
                    $customer->created_at->format('M d, Y'),
                ]);
            }
    
            fclose($file);
        };
    
        return response()->stream($callback, 200, $headers);
    }
    
}
