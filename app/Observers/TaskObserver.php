<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\User;
use App\Models\Telephone;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        //

        // Fetch all users assigned to the task
        $assignedUsers = DB::table('user_tasks')->where('task_id', $task->id)->pluck('user_id');
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        //
    }
}
