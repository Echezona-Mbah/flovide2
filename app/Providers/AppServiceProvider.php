<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

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
    Route::prefix('api') // The 'api' prefix is automatically added
        ->middleware('api') // Apply the 'api' middleware group
        ->namespace($this->namespace)
        ->group(base_path('routes/api.php')); // Path to the api.php routes file
}
    
    
    
    
}
