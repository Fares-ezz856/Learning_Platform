<?php

namespace App\Providers;

use App\Interface\AdminInterface;
use App\Interface\InstructorInterface;
use App\Interface\StudentInterface;
use App\Repository\AdminRepository;
use App\Repository\InstructorRepository;
use App\Repository\StudentRepository;
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
        $this->app->bind(AdminInterface::class,AdminRepository::class);
        $this->app->bind(InstructorInterface::class,InstructorRepository::class);
        $this->app->bind(StudentInterface::class,StudentRepository::class);
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
