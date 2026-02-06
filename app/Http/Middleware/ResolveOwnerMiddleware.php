<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\TeamMembers;

class ResolveOwnerMiddleware
{
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {

            $authUser = auth()->user();

            // ✅ Check if this user is a team member (admin under an owner)
            $teamMembership = TeamMembers::where('user_id', $authUser->id)->first();

            // ✅ If team member → use owner_id
            // ✅ Else → user is owner of their own business
            $ownerId = $teamMembership
                ? $teamMembership->owner_id
                : $authUser->id;

            // ✅ Store in session (For Web)
            session(['owner_id' => $ownerId]);

            // ✅ Attach to request (For API & Web - more reliable!)
            $request->merge(['owner_id' => $ownerId]);

            // (OPTIONAL) Debug Log
            // \Log::info("Resolved Owner ID: {$ownerId} for User ID: {$authUser->id}");
        }

        return $next($request);
    }
}
