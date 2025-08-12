<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\TeamMembers;

class ResolveOwnerMiddleware
{
    public function handle($request, Closure $next)
{
    $ownerId = null;

    if (auth()->check()) {
        $authUser = auth()->user();

        $teamMembership = TeamMembers::where('user_id', $authUser->id)->first();

        $ownerId = $teamMembership
            ? $teamMembership->userOwner->id
            : $authUser->id;

        session(['owner_id' => $ownerId]);
    }

    // \Log::info('Middleware running. Owner ID: ' . ($ownerId ?? 'none'));

    return $next($request);
}

}
