<?php

namespace App\Providers;

use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function(User $user) {
            if ($user->role->permissions->pluck('name')->contains('full_admin')) {
                return true;
            }
        });

        Gate::before(function(User $user, $permission) {
            if ($user->role->permissions->pluck('name')->contains($permission)) {
                return true;
            }
        });
    }
}
