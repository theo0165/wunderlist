<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TodoList;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View|Factory
    {
        return view('task.index', [
            'tasks' => Auth::user()->tasks
        ]);
    }

    public function show(string $id): View|Factory
    {
        $task = Auth::user()->tasks()->where('tasks.uuid', $id)->firstOrFail();

        return view('task.show', [
            'task' => $task,
            'lists' => Auth::user()->lists
        ]);
    }

    public function patch(string $id): Redirector|RedirectResponse|never
    {
        $task = Auth::user()->tasks()->firstOrFail(['tasks.*', 'todo_lists.user_id']);

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

            return redirect()->back();
        } else {
            return abort(400);
        }

        return abort(400);
    }

    public function delete(string $id): Redirector|RedirectResponse
    {
        $task = Auth::user()->tasks()->where('tasks.uuid', $id)->firstOrFail();

        $task->delete();

        return redirect()->back();
    }
}
