<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        RateLimiter::for('api',function(Request $request){
            if($request->user('admin')){
                return Limit::perMinute(3)->by($request->user('admin')->id);
            }
            if($request->user('instructor')){
                return limit::perMinute(3)->by($request->user('instructor')->id);
            }
            if($request->user('student')){
                return limit::perMinute(3)->by($request->user('student')->id);
            }
        });
    }
}
