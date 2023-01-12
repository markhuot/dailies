<?php

namespace App\Collections;

use App\Models\Task;
use App\Models\Timer;
use Carbon\CarbonInterval;

class TimerCollection extends \Illuminate\Database\Eloquent\Collection
{
    public function isRunning()
    {
        return $this->where('stopped_at', null)->count() > 0;
    }

    public function duration()
    {
        $totalSeconds = $this
            ->map(fn ($timer) => $timer->started_at->diffInSeconds(($timer->stopped_at ?? now()), false))
            ->sum();

        return \Carbon\CarbonInterval::seconds($totalSeconds)->cascade();
    }

    public function adjust(Task $task, string|CarbonInterval $interval)
    {
        if (is_string($interval)) {
            $interval = CarbonInterval::fromString($interval);
        }

        $existingSeconds = $this->duration()->total('seconds');
        $desiredSeconds = $interval->total('seconds');
        $diffSeconds = $desiredSeconds - $existingSeconds;

        $timer = new Timer;
        $timer->started_at = $task->date->startOfDay();
        $timer->stopped_at = $task->date->startOfDay()->addSeconds($diffSeconds);
        $task->timers()->save($timer);
        $task->refresh();
        $task->touch();
    }
}
