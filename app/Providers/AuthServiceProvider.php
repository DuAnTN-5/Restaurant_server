<?php

namespace App\Providers;

use App\Models\Staff;
use App\Policies\StaffPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Staff::class => StaffPolicy::class, // Ánh xạ Staff với StaffPolicy
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Nếu cần, bạn có thể định nghĩa các Gate ở đây
        // Gate::define('some-action', function ($user) {
        //     return $user->role === 'admin';
        // });
    }
}
