<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Task;
use Auth;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use InvalidArgumentException;

class TodayController extends Controller
{
    /** @return void  */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return View|Factory
     * @throws InvalidArgumentException
     * @throws BindingResolutionException
     */
    public function index(): View|Factory
    {
        $tasks = Auth::user()->tasks()->whereDate('deadline', '=', date('Y-m-d', strtotime("now")))->get();

        return view('today.index', [
            'tasks' => $tasks
        ]);
    }
}
