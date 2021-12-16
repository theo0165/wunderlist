<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(string $id)
    {
        return view('task.show');
    }

    public function patch(string $id)
    {
    }

    public function delete(string $id)
    {
        $task = Task::where('uuid', '=', $id)->first();
        if ($task != null) {
            $task->delete();
        } else {
            return abort(404);
        }

        return redirect()->back();
    }
}
