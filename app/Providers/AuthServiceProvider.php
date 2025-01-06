<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\ProjectsUsers;
use App\Models\Task;
use App\Policies\ProjectPolicy;
use App\Policies\ProjectsUsersPolicy;
use App\Policies\TaskPolicy;
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
        //
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
