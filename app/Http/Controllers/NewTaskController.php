<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TodoList;
use Auth;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\InvalidCastException;
use Illuminate\Database\LazyLoadingViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;
use InvalidArgumentException;
use Vinkla\Hashids\Facades\Hashids;
use LogicException;

class NewTaskController extends Controller
{
    /** @return void  */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display create new task page.
     *
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function show(): View|Factory
    {
        return view('newTask.show', [
            'lists' => Auth::user()->lists,
            'selectedList' => request()->query('list') ?? null
        ]);
    }

    /**
     * Create new task from form data.
     *
     * @return Redirector|RedirectResponse
     * @throws BindingResolutionException
     * @throws InvalidArgumentException
     * @throws InvalidCastException
     * @throws LazyLoadingViolationException
     * @throws LogicException
     */
    public function store(): Redirector|RedirectResponse
    {
        if (request()->has('list')) {
            request()->merge([
                'list' => strval(Hashids::decode(request()->get('list'))[0])
            ]);
        }

        // https://laravel.com/docs/8.x/validation#specifying-a-custom-column-name
        $data = request()->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['string'],
            'deadline' => ['date', 'nullable'],
            'list' => [
                'required', 'string', 'max:5',
                // Check if list exists in database and if current user owns said list.
                Rule::exists('todo_lists', 'id')->where(function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                })
            ]
        ]);

        Task::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'deadline' => $data['deadline'],
            'list_id' => $data['list']
        ]);

        return redirect(route('list.show', Hashids::encode($data['list'])), 201);
    }
}
