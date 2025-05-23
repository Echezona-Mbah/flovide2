<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

use Illuminate\Http\Response as HttpResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    // public function boot(): void
    // {
    //     $this->routes(function () {
    //         Route::middleware('api')
    //             ->prefix('api') 
    //             ->group(base_path('routes/api.php'));
    
    //         Route::middleware('web')
    //             ->group(base_path('routes/web.php'));
    //     });
    // }

    public function map()
    {
        $this->mapApiRoutes(); // This will map your api routes
        $this->mapWebRoutes(); // If you need web routes
    }

    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api') 
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php')); 
    }

    public function boot()
    {

              // Force HTTPS in production
             if (app()->environment('production')) {
                URL::forceScheme('https');
            }
    
            // Add global security headers to all responses
            app('router')->pushMiddlewareToGroup('web', \App\Http\Middleware\SecurityHeaders::class);

                // Register security headers middleware only in production (optional)
            // if (App::environment('production')) {
            //     app('router')->pushMiddlewareToGroup('web', \App\Http\Middleware\SecurityHeaders::class);
            // }




        // Only minify in production, optional
        if (App::environment('production')) {
                app()->terminating(function () {
                $response = response();
    
                if (
                    $response instanceof HttpResponse &&
                    str_contains($response->headers->get('Content-Type'), 'text/html')
                ) {
                    $content = $response->getContent();
    
                    $minified = preg_replace([
                        '/<!--(.*?)-->/s', 
                        '/\s{2,}/',
                        '/>\s+</', 
                    ], [
                        '',
                        ' ',
                        '><',
                    ], $content);
    
                    $response->setContent($minified);
                }
            });
        }
    }

    

    
    
    
    
}
