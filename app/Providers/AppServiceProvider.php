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
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $device_status = [
            '0' => 'Offline',
            '3' => 'Online'
        ];

        $background_colors = [
            '0' => 'bg-danger',
            '1' => 'bg-secondary',
            '2' => 'bg-warniing',
            '3' => 'bg-info',
        ];

        config(['device_status' => $device_status]);
        View::share(['device_status' => $device_status]);
        View::share(['device_status' => $background_colors]);
    }
}
