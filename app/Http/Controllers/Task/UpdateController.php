<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(Task $task, Request $request)
    {
        $attributes = $request->validate([
            'task.name' => ['string'],
            'task.complete' => ['boolean'],
        ]);

        $task->update($attributes['task']);
        $task->save();

        return redirect('/');
    }
}
