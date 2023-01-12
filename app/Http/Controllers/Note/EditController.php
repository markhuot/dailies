<?php

namespace App\Http\Controllers\Note;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class EditController extends Controller
{
    public function __invoke(Task $task)
    {
        return view('note.edit')->with(['task' => $task]);
    }
}
