<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Idea;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //Role
        Gate::define('admin', function(User $user) :bool{
            return (bool) $user->is_admin;
        });

        //Permission
        Gate::define('idea.delete', function(User $user, Idea $idea) :bool{
            return ((bool) $user->is_admin || $user->id === $idea->user_id);
        });

        Gate::define('idea.edit', function(User $user, Idea $idea) :bool{
            return ((bool) $user->is_admin || $user->id === $idea->user_id);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}
