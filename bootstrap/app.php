<?php

use App\Http\Middleware\EnsureIpWhitelisted;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
        'admin.auth' => \App\Http\Middleware\AdminAuth::class,
        'business.verified' => \App\Http\Middleware\EnsureBusinessVerified::class,
         'ip.whitelist' => EnsureIpWhitelisted::class,
    ]);
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
            // ✅ Add this for API
        $middleware->api(append: [
            \App\Http\Middleware\ResolveOwnerMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
