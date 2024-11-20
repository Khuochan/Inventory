<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    protected $namespace = 'App\Http\Controllers';

    protected $namespaceHR = 'App\Http\Controllers\HR';

    protected $namespaceLog = 'App\Http\Controllers\LogFile';

    protected $namespaceIMS = 'App\Http\Controllers\Inventory';

    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        Route::middleware('api')
            ->prefix('api')
            ->namespace($this->namespace)
            ->group(function () {
                // Default namespace routes
                require base_path('routes/api.php');
            });

        Route::middleware('api')
            ->prefix('api/hr') // Adjust the prefix as needed
            ->namespace($this->namespaceHR)
            ->group(function () {
                // HR namespace routes
                require base_path('routes/api.php');
            });

        Route::middleware('api')
            ->prefix('api/v2') // Adjust the prefix as needed
            ->namespace($this->namespaceLog)
            ->group(function () {
                // HR namespace routes
                require base_path('routes/api.php');
        });

        Route::middleware('api')
            ->prefix('api/v3') // Adjust the prefix as needed
            ->namespace($this->namespaceIMS)
            ->group(function () {
                // HR namespace routes
                require base_path('routes/api.php');
        });

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        // });
    }
}
