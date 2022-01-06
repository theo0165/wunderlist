<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Task;
use Auth;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class TodayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View|Factory
    {
        $tasks = Auth::user()->tasks()->whereDate('deadline', '=', date('Y-m-d', strtotime("now")))->get();

        return view('today.index', [
            'tasks' => $tasks
        ]);
    }
}
