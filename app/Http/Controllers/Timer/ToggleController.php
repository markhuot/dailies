<?php

namespace App\Http\Controllers\Timer;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Timer;

class ToggleController extends Controller
{
    public function __invoke(Task $task)
    {
        $runningTimer = $task->timers()->where('stopped_at', null)->first();
        if ($runningTimer) {
            $runningTimer->stopped_at = now();
            $timer = $runningTimer;
        } else {
            $timer = new Timer();
            $timer->started_at = now();
        }

        $task->timers()->save($timer);

        return redirect('/');
    }
}
