<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(User $user, Project $project): bool
    {
        return $project->members()
            ->where('user_id', $user->id)
            ->where('is_accepted', true)
            ->exists();
    }

    public function update(User $user, Project $project): bool
    {
        if($project->owner == $user->id) return true;
        else return false;
    }

    public function delete(User $user, Project $project): bool
    {
        if($project->owner == $user->id) return true;
        else return false;
    }
}
