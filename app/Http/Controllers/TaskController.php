<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        return view('task.show');
    }

    public function patch(string $id)
    {
    }

    public function delete(string $id)
    {
    }
}
