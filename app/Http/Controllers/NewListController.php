<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;

class NewListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        return view('newList.show');
    }

    public function store()
    {
        $data = request()->validate([
            'title' => ['string', 'max:255']
        ]);

        $list = new TodoList($data);

        $list->save();

        return redirect("/list/" . $list->uuid);
    }
}
