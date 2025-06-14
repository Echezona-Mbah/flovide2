<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Beneficia;
use App\Models\Countries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddBeneficiariesController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
    
        $beneficias = Beneficia::where('user_id', $user->id)
        ->paginate(3);
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Beneficia records retrieved successfully',
                'data' => $beneficias,
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ], 200);
        }
    
        return view('business.beneficiaries', compact('beneficias'));
    }

    public function create() {
        $user = auth()->user();
        $countries = Countries::all();
        $banks = Bank::all();
        $beneficiaries = Beneficia::where('user_id', $user->id)
        ->paginate(3);;
        return view('business.add_beneficia',compact('countries', 'banks','beneficiaries'));
    }

    public function store(Request $request)
    {
                //dd($request->all());

        $rules = [
            'bank' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'account_number' => 'required|string',
            'account_name' => 'required|string|max:255',
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
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
    
        $beneficia = Beneficia::create([
            'bank' => $request->bank,
            'country_id' => $request->country_id,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'user_id' => $request->user()?->id ?? auth()->id(), 
        ]);
    
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Beneficia created successfully',
                'data' => $beneficia,
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ], 201);
        }
    
        return redirect()->route('add_beneficias.create')->with('success', 'Beneficia created successfully.');
    }

    public function edit($id)
    {
        $user = auth()->user();
        $beneficia = Beneficia::where('user_id', $user->id)->where('id', $id)->firstOrFail();
        $countries = Countries::all();
        $banks = Bank::all();
        $beneficiaries = Beneficia::where('user_id', $user->id)->paginate(3);
    
        return view('business.edit_beneficia', compact('beneficia', 'countries', 'banks', 'beneficiaries'));
    }
    
    
    
    

    public function update(Request $request, $id)
    {
        //dd($request->all());
        $rules = [
            'bank' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'account_number' => 'required|string',
            'account_name' => 'required|string|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);

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

        $beneficia = Beneficia::findOrFail($id);

        // Optional: Authorize that the user owns this record
        if ($beneficia->user_id !== ($request->user()?->id ?? auth()->id())) {
            abort(403, 'Unauthorized');
        }

        $beneficia->update([
            'bank' => $request->bank,
            'country_id' => $request->country_id,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Beneficia updated successfully',
                'data' => $beneficia,
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ], 200);
        }

        return redirect()->route('beneficias')->with('success', 'Beneficia updated successfully.');
    }


    public function destroy(Request $request, $id)
    {
        $beneficia = Beneficia::findOrFail($id);

        if ($beneficia->user_id != auth()->id()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized to delete this beneficia',
                ], 403);
            } else {
                return redirect()->back()->withErrors(['message' => 'Unauthorized to delete this beneficia']);
            }
        }
        $beneficia->delete();
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Beneficia deleted successfully',
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ]);
        }
        return redirect()->route('customers.index')->with('status', 'Beneficia deleted successfully');
    }
    
    public function search(Request $request)
    {
        $query = $request->input('query');
    
        $beneficiaries = Beneficia::with('country')
            ->where('account_name', 'like', "%{$query}%")
            ->orWhere('bank', 'like', "%{$query}%")
            ->orWhere('account_number', 'like', "%{$query}%")
            ->orWhereHas('country', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->get();
    
        return response()->json($beneficiaries);
    }
    


    
}
