<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\ProjectsUsers;
use App\Models\Task;
use App\Policies\ProjectPolicy;
use App\Policies\ProjectsUsersPolicy;
use App\Policies\TaskPolicy;
use Illuminate\Support\Facades\Gate;
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
        //
    }
}
