<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(string $id)
    {
        $task = Task::select([
            'tasks.uuid',
            'tasks.title',
            'tasks.description',
            'tasks.deadline',
            'tasks.list_id'
        ])->join('todo_lists', 'tasks.list_id', '=', 'todo_lists.id')->where('tasks.uuid', $id)->where('todo_lists.user_id', Auth::user()->id)->first();

        if ($task === null) {
            return abort(404);
        }

        return view('task.show', [
            'task' => $task,
            'lists' => TodoList::where('user_id', Auth::user()->id)->get()
        ]);
    }

    public function patch(string $id)
    {
        $task = Task::select([
            'tasks.*',
            'todo_lists.user_id'
        ])->join('todo_lists', 'tasks.list_id', '=', 'todo_lists.id')->where('tasks.uuid', $id)->where('todo_lists.user_id', Auth::user()->id)->first();

        if ($task === null) {
            return abort(404);
        }

        if (request()->has('function') && request()->get('function') === "complete") {
            $data = request()->validate([
                'completed' => ['string']
            ]);

            $task->update(['completed' => isset($data['completed']) ? true : false]);

            return abort(202);
        } else if (request()->has('function') && request()->get('function') === "edit") {
            $data = request()->validate([
                'title' => ['required', 'string', 'max:255'],
                'description' => ['string'],
                'deadline' => ['date', 'nullable'],
                'list' => [
                    'required', 'string', 'max:5',
                    Rule::exists('todo_lists', 'uuid')->where(function ($query) {
                        return $query->where('user_id', Auth::user()->id);
                    })
                ]
            ]);

            $task->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'deadline' => $data['deadline'],
                'list_id' => TodoList::where('uuid', $data['list'])->first('id')['id']
            ]);

            /*
            $task->title = $data['title'];
            $task->description = $data['description'];
            $task->deadline = $data['deadline'];
            $task->list_id = TodoList::where('uuid', $data['list'])->first('id')['id'];
            $task->save();
            */

            return redirect()->back();
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
