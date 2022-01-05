<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TodoList;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;

class NewTaskController extends Controller
{
    public function show()
    {
        return view('newTask.show', [
            'lists' => Auth::user()->lists,
            'selectedList' => request()->query('list') ?? null
        ]);
    }

    public function store()
    {
        // https://laravel.com/docs/8.x/validation#specifying-a-custom-column-name
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

        Task::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'deadline' => $data['deadline'],
            'list_id' => TodoList::where('uuid', $data['list'])->first('id')['id']
        ]);

        return redirect(route('list.show', $data['list']));
    }
}
