<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureBusinessVerified
{
    // public function handle(Request $request, Closure $next)
    // {
    //     $user = auth()->user();

    //     if ($user && ! $user->isFullyVerified()) {

    //         // allow verification + dashboard only
    //         if ($request->routeIs('compliance', 'verification.*')) {
    //             return $next($request);
    //         }

    //         return redirect()
    //             ->route('compliance')
    //             ->with('error', 'Complete verification to continue');
    //     }

    //     return $next($request);
    // }

public function handle(Request $request, Closure $next)
{
    $user = auth()->user();

    if ($user && ! $user->isFullyVerified()) {

        if ($request->routeIs(
            'compliance*',
            'verification.*',
            'logout',
            'sumsub.webhook',
            'dashboard',
            'profile*'
        )) {
            return $next($request);
        }

        return redirect()
            ->route('compliance')
            ->with('error','Complete verification to continue');
    }

    return $next($request);
}
}
