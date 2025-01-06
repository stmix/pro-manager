<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Task $task)
    {
        if($task->project()->first()->owner == $user->id)
        {
            return true;
        }
        else if($task->author === $user->id)
        {
            return true;
        }
        else return false;
    }
}
