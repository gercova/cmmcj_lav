<?php

namespace App\Providers;

use App\Models\Enterprise;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
    public function boot(): void
    {
        setlocale(LC_TIME, 'es_ES.utf8', 'es_ES', 'es');
        // Usar un View Composer para compartir variables
        View::composer('layouts.app', function ($view) {
            $enterprise = Enterprise::where('id', 1)->get();
            $view->with(['enterprise' => $enterprise]);
        });
    }
}
