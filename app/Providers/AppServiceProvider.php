<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use App\Models\GeneralNotification;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\View;
use App\Models\AdminNotification;

use Illuminate\Http\Response as HttpResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The namespace for the application's controllers.
     *
     * @var string|null
     */
    protected $namespace = 'App\\Http\\Controllers';
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }


 protected function mapWebRoutes()
{
   Route::middleware(['web', \App\Http\Middleware\ResolveOwnerMiddleware::class])
    ->namespace($this->namespace)
    ->group(base_path('routes/web.php'));

}



    

    /**
     * Bootstrap any application services.
     */


    public function map()
    {
        $this->mapApiRoutes(); // This will map your api routes
        $this->mapWebRoutes(); // If you need web routes
    }

  protected function mapApiRoutes()
{
    Route::prefix('api')
        ->middleware(['api', \App\Http\Middleware\ResolveOwnerMiddleware::class])
        ->namespace($this->namespace)
        ->group(base_path('routes/api.php'));
}


    public function boot()
    {

              // Force HTTPS in production
             if (app()->environment('production')) {
                URL::forceScheme('http');
            }
    
      


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


            // Tell Laravel to use our GeneralNotification model
    DatabaseNotification::resolveRelationUsing('morph', function () {
        return GeneralNotification::class;
    });


     View::composer('admin.header', function ($view) {
        $view->with('unreadReferralAlerts', AdminNotification::whereNull('read_at')
            ->where('type', 'referral_bonus')
            ->latest()
            ->get());
    });

    }

    
protected $listen = [
    \App\Events\UserRegistered::class => [
        \App\Listeners\SendUserRegisteredNotification::class,
    ],
    // You can add more events like TransactionCreated, BalanceCreated, etc.
];
    
    

    
}
