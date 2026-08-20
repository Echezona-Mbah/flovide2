<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminActivityLog;
use Jenssegers\Agent\Agent;


class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle($request, Closure $next)
    // {
    //     if (!Auth::guard('admin')->check()) {
    //         return redirect()->route('admin.login')
    //             ->with('error', 'You must log in as admin.');
    //     }

    //     return $next($request);
    // }


public function handle($request, Closure $next)
{
    if (!Auth::guard('admin')->check()) {
        return redirect()->route('admin.login')
            ->with('error','You must log in as admin.');
    }

    $timeout = 600;

    if (session()->has('lastActivityTime')) {

        $inactive = time() - session('lastActivityTime');

        if ($inactive > $timeout) {

            Auth::guard('admin')->logout();
            session()->flush();

            return redirect()->route('admin.login')
                ->with('error','Session expired.');
        }
    }

    session(['lastActivityTime' => time()]);

    $admin = Auth::guard('admin')->user();

    $agent = new Agent();

    AdminActivityLog::create([
        'admin_id' => $admin->id,
        'activity' => 'Visited '.$request->path(),
        'ip_address' => $request->ip(),
        'device' => $agent->platform().' '.$agent->browser()
    ]);

    return $next($request);
}




}
