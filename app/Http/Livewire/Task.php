<?php

namespace App\Http\Livewire;

use App\Models\Timer;
use Carbon\CarbonInterval;
use Livewire\Component;

class Task extends Component
{
    public \App\Models\Task $task;
    public string $taskName;

    public function mount(\App\Models\Task $task)
    {
        $this->task = $task;
        $this->taskName = $task->name;
    }

    public function render()
    {
        return view('livewire.task');
    }

    public function updatedTaskName($value)
    {
        $this->task->name = $value;
        $this->task->save();
    }

    public function toggleComplete()
    {
        $this->task->complete = !$this->task->complete;
        $this->task->save();
    }

    public function toggleTimer()
    {
        $runningTimer = $this->task->timers()->where('stopped_at', null)->first();

        if ($runningTimer) {
            $runningTimer->stopped_at = now();
            $timer = $runningTimer;
        } else {
            $timer = new Timer();
            $timer->started_at = now();
        }

        $this->task->timers()->save($timer);
        $this->task->refresh();
        $this->task->touch();
    }

    public function updateTimer(string $interval)
    {
        $this->task->timers->adjust($this->task, $interval);
    }

    public function updateName(string $name)
    {
        $this->task->name = $name;
        $this->task->save();
    }
}
