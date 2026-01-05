<?php

namespace App\Providers;

use App\Helper\GeneralHelper;
use App\Listeners\UpdateLogout;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Logout;

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
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $user = auth()->user();
                $path = $user->url_image ? GeneralHelper::getImageUrl( $user->url_image ) :null;
                $view->with('global_image_url', $path);
            }
        });

        Event::listen(
            Logout::class,
            UpdateLogout::class,
        );


    }
}
