<?php

namespace App\Http\Controllers\business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Remita;
use App\Models\Subaccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class RemitaController extends Controller
{
    //
    public function index()
    {
        return view('business.remita');
    }

    public function create(Request $request)
    {

        $user = Auth::user();
        $subaccounts = Subaccount::where('user_id', $user->id)->get();
        
        if ($request->expectsJson()) {
            if ($subaccounts->isEmpty()) {
                return response()->json(['message' => 'No subaccount found.'], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Subaccounts retrieved successfully.',
                'data' => $subaccounts,
            ], 200);
        }
        return view('business.remitaCreate', ['subaccounts' => $subaccounts]);
    }
    public function update()
    {
        return view('business.remitaPayment');
    }
    public function store(Request $request)
    {
        $request->validate([
            'cover_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // 5MB
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'rrr' => 'nullable|string|max:50',
            'service_type' => 'required|string',
            'subaccount' => 'required|string',
            'currency' => 'required|string',
            'visibility' => 'required|string',
        ]);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('cover_images', 'public');
            $data['cover_image'] = $path;
        }

        $user = Auth::user();

        // Save to DB
        Remita::create([
            'user_id' => $user->id,
            'cover_image' => $request->cover_image ?? null,
            'title' => $request->title,
            'amount' => $request->amount,
            'rrr' => $request->rrr,
            'service_type' => $request->service_type,
            'subaccount' => $request->subaccount,
            'currency' => $request->currency,
            'visibility' => $request->visibility,
        ]);

        return redirect()->route('remita.index')->with('success', 'Remita page created successfully.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $remita = Remita::where('id', $id)->where('user_id', $user->id)->first();

        if (!$remita) {
            return redirect()->route('remita.index')->with('error', 'Remita page not found or you do not have permission to delete it.');
        }

        $remita->delete();

        return redirect()->route('remita.index')->with('success', 'Remita page deleted successfully.');
    }
}
