<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Auth;
use Illuminate\Http\Request;

class NewTaskController extends Controller
{
    public function show()
    {
        return view('newTask.show', [
            'lists' => TodoList::where('user_id', Auth::user()->id)->get(),
            'selectedList' => request()->query('list') ?? null
        ]);
    }

    public function store()
    {
    }
}
