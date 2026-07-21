<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\TeamMembers;
use App\Models\User;
use Illuminate\Http\Request;

class referralLinkController extends Controller
{
    private function resolveOwnerAndMember(Request $request, ?User $actor): array
    {
        if (! $actor) {
            return [null, null, null, null]; // ownerId, memberId, role, ownerUser
        }

        $team = TeamMembers::where('user_id', $actor->id)->first();

        $ownerId  = $team ? $team->owner_id : $actor->id;
        $memberId = $team ? $actor->id : null;
        $role     = $team ? $team->role : 'Owner';
        $owner    = User::find($ownerId);

        return [$ownerId, $memberId, $role, $owner];
    }

    public function index(Request $request)
    {
        $actor = auth()->user();
        [$ownerId, $memberId, $role, $owner] = $this->resolveOwnerAndMember($request, $actor);

        if (! $owner) {
            return $request->expectsJson()
                ? response()->json([
                    'success' => false,
                    'message' => 'Owner account not found',
                    'code' => 'OWNER_NOT_FOUND',
                    'data' => null
                ], 422)
                : back()->with('error', 'Owner account not found');
        }

        $referralLink = $owner->referral_link;

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Referral link fetched successfully',
                'code' => 'REFERRAL_LINK_FETCHED',
                'data' => [
                    'owner_id' => $owner->id,
                    'referral_link' => $referralLink,
                ],
            ], 200);
        }

        return view('business.referral', compact('referralLink'));
    }
}