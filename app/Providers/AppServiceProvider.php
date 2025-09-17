<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    //jiheui lee : to get rid of deprecated messages
    public function register(): void
    {
        if (config('app.debug')) {
            error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            // Grab from session (default to empty array)
            $dismissed = session('dismissed_modals', []);

            // Share with all Blade views
            $view->with('dismissedModals', $dismissed);
        });
    }
}
