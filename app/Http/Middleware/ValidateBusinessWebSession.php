<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidateBusinessWebSession
{
    public function handle(Request $request, Closure $next): Response
    {
        // Make sure the web user is authenticated
        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Please login again.');
        }

        $user = Auth::user();

        // Get the session ID stored in this browser
        $browserSessionId = session('active_session_id');

        // Get the current active session from database
        $activeSessionId = $user->active_session_id;

        /*
         * If they don't match, another device/session
         * has logged into this account.
         */
        if (
            !$browserSessionId ||
            !$activeSessionId ||
            !hash_equals(
                (string) $activeSessionId,
                (string) $browserSessionId
            )
        ) {
            Auth::logout();

            session()->forget('active_session_id');
            session()->invalidate();
            session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Your account was signed in on another device. Please login again.'
                );
        }

        return $next($request);
    }
}