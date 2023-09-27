<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

use Gate;

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
        
        Gate::define('admin',function($user){
            return $user->role == 'admin';
        });

        Gate::define('user',function($user){
            return $user->role == 'user';
        });
        Gate::define('fs',function($user){
            return $user->role == 'fs';
        });
        Gate::define('dba',function($user){
            return $user->role == 'dba';
        });
        Gate::define('am',function($user){
            return $user->role == 'am';
        });
        Gate::define('nsm',function($user){
            return $user->role == 'nsm';
        });
        Gate::define('bu',function($user){
            return $user->role == 'bu';
        });

    }
}
