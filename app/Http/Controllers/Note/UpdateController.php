<?php

namespace App\Http\Controllers\Note;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Task;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(Task $task, Request $request)
    {
        $attributes = $request->validate([
            'note.contents' => ['nullable', 'string'],
        ]);

        $note = Note::where('task_id', '=', $task->id)->firstOrNew();
        $note->contents = $attributes['note']['contents'];
        $task->note()->save($note);

        return redirect()->route('dashboard.show');
    }
}
