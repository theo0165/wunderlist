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
        $list = TodoList::where('uuid', $id)->where('user_id', Auth::user()->id)->firstOrFail();
        $tasks = Task::where('list_id', '=', $list->id)->get();

        return view('list.show', [
            'list' => $list,
            'tasks' => $tasks
        ]);
    }

    public function edit(string $id)
    {
    }

    public function patch(string $id)
    {
    }

    public function delete(string $id)
    {
        $list = TodoList::where('uuid', $id)->where('user_id', Auth::user()->id)->first();
        if ($list != null) {
            $list->delete();
        } else {
            return abort(404);
        }

        return redirect("/");
    }
}
