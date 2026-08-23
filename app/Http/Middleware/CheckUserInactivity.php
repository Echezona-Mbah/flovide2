<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserInactivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $timeout = 900; // 15 minutes

        if (session()->has('lastActivityTime')) {
            $inactive = time() - session('lastActivityTime');

            if ($inactive >= $timeout) {
                Auth::logout();

                //clear session
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->with('error', 'Your session has expired.');
            }
        }

        session(['lastActivityTime' => time()]);
        
        return $next($request);
    }
}
