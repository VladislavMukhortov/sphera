<?php

namespace App\Providers;

use App\Models\{Skill, Staff, Setting, User};
use App\Policies\StaffPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
//         'App\Models\Model' => 'App\Policies\ModelPolicy',
            Staff::class => StaffPolicy::class,
            Skill::class => StaffPolicy::class,
            User::class => StaffPolicy::class,
            Setting::class => StaffPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
