<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(string $id)
    {
        $list = Auth::user()->lists()->where('uuid', $id)->firstOrFail();

        return view('list.show', [
            'list' => $list,
            'tasks' => $list->tasks
        ]);
    }

    public function edit(string $id)
    {
        $list = Auth::user()->lists()->where('uuid', $id)->firstOrFail();

        return view('list.edit', [
            'list' => $list
        ]);
    }

    public function patch(string $id)
    {
        if (request()->has('title')) {
            $data = request()->validate([
                'title' => ['string', 'max:255']
            ]);

            $list = Auth::user()->lists()->where('uuid', $id)->firstOrFail();
            $list->update(['title' => $data['title']]);

            return redirect(route('list.show', $id));
        } else {
            return abort('400');
        }
    }

    public function delete(string $id)
    {
        $list = Auth::user()->lists()->where('uuid', $id)->firstOrFail();

        # Delete all tasks when deleting list.
        foreach ($list->tasks as $task) {
            $task->delete();
        }

        $list->delete();

        return redirect("/");
    }
}
