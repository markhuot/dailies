<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __invoke(Request $request)
    {
        $attributes = $request->validate([
            'task.name' => ['required'],
            'task.date' => [],
        ]);

        $attributes['task']['sort'] = Task::where('date', $attributes['task']['date'])->count();

        $task = Task::create($attributes['task']);
        $request->user()->tasks()->save($task);

        return redirect('/');
    }
}
