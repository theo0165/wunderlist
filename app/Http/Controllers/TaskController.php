<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

    //TODO: Fix checkbox not working
    public function patch(string $id)
    {
        $task = Task::select('*')->join('todo_lists', 'tasks.list_id', '=', 'todo_list.id')->where('tasks.uuid', $id)->where('todo_lists.user_id', Auth::user()->id)->first();

        if ($task === null) {
            return abort(404);
        }

        if (request()->has('function') && request()->get('function') === "complete") {
            $data = request()->validate([
                'completed' => ['string']
            ]);

            $task->update(['completed' => isset($data['completed']) ? true : false]);

            return abort(202);
        } else {
            return abort(400);
        }

        return abort(400);
    }

    public function delete(string $id)
    {
        $task = Task::select('*')->join('todo_lists', 'tasks.list_id', "=", "todo_lists.id")->where('tasks.uuid', $id)->where('todo_lists.user_id', Auth::user()->id)->first();
        if ($task != null) {
            $task->delete();
        } else {
            return abort(404);
        }

        return redirect()->back();
    }
}
