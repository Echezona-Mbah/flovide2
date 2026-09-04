<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use App\Models\PromoCodeRedemption;
use App\Models\User;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PromoCodeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $allowedPerPage = [25, 50, 100, 250, 500];
        $perPage = (int) $request->input('per_page', 25);

        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 25;
        }

        $query = PromoCode::query();

        if ($search) {
            $query->where('code', 'like', "%{$search}%");
        }

        $promoCodes = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $promoCodes->getCollection()->transform(function ($promo) {
            $promo->owner = $promo->ownerModel();
            $promo->redemption_count = PromoCodeRedemption::where('promo_code_id', $promo->id)->count();
            $promo->total_rewarded   = PromoCodeRedemption::where('promo_code_id', $promo->id)->sum('reward_amount');
            return $promo;
        });

        return view('admin.promocodes', compact('promoCodes', 'search', 'perPage', 'allowedPerPage'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'owner_type'   => 'required|in:business,personal',
            'owner_email'  => 'required|email',
            'reward_type'  => 'required|in:percent,fixed',
            'reward_value' => 'required|numeric|min:0',
        ]);

        if ($request->reward_type === 'percent' && $request->reward_value > 100) {
            return back()->withErrors(['reward_value' => 'Percentage reward cannot exceed 100.']);
        }

        $ownerModel = $request->owner_type === 'business'
            ? User::where('email', $request->owner_email)->where('typeofuser', 'business')->first()
            : Personal::where('email', $request->owner_email)->first();

        if (!$ownerModel) {
            return back()->withErrors(['owner_email' => 'No matching ' . $request->owner_type . ' account found with that email.']);
        }

        do {
            $code = strtoupper(Str::random(8));
        } while (PromoCode::where('code', $code)->exists());

        $promo = PromoCode::create([
            'code'                => $code,
            'owner_type'          => $request->owner_type,
            'owner_id'            => $ownerModel->id,
            'reward_type'         => $request->reward_type,
            'reward_value'        => $request->reward_value,
            'status'              => 'active',
            'created_by_admin_id' => Auth::guard('admin')->id(),
        ]);

        return back()->with('success', "Promo code {$code} generated for {$ownerModel->email}.");
    }

    public function toggleStatus($id)
    {
        $promo = PromoCode::findOrFail($id);
        $promo->status = $promo->status === 'active' ? 'inactive' : 'active';
        $promo->save();

        return back()->with('success', 'Promo code status updated.');
    }

    public function destroy($id)
    {
        PromoCode::findOrFail($id)->delete();
        return back()->with('success', 'Promo code deleted.');
    }

    public function generateForOwner(Request $request, string $ownerType, $ownerId)
{
    $request->validate([
        'reward_type'  => 'required|in:percent,fixed',
        'reward_value' => 'required|numeric|min:0',
    ]);

    if ($request->reward_type === 'percent' && $request->reward_value > 100) {
        return back()->withErrors(['reward_value' => 'Percentage reward cannot exceed 100.']);
    }

    $ownerModel = $ownerType === 'business'
        ? User::find($ownerId)
        : Personal::find($ownerId);

    if (!$ownerModel) {
        return back()->with('error', 'Owner account not found.');
    }

    do {
        $code = strtoupper(Str::random(8));
    } while (PromoCode::where('code', $code)->exists());

    PromoCode::create([
        'code'                => $code,
        'owner_type'          => $ownerType,
        'owner_id'            => $ownerModel->id,
        'reward_type'         => $request->reward_type,
        'reward_value'        => $request->reward_value,
        'status'              => 'active',
        'created_by_admin_id' => Auth::guard('admin')->id(),
    ]);

    return back()->with('success', "Promo code {$code} generated successfully.");
}


}