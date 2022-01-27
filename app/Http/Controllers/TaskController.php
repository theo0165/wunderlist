<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TodoList;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\InvalidCastException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\LazyLoadingViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use LogicException;
use Response;
use Vinkla\Hashids\Facades\Hashids;

class TaskController extends Controller
{
    /** @return void  */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display all tasks belonging to current user
     *
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function index(): View|Factory
    {
        return view('task.index', [
            'tasks' => Auth::user()->tasks
        ]);
    }

    public function search(Request $request): View|Factory
    {
        $term = $request->get('term');
        $tasks = Auth::user()->tasks()->where('tasks.title', 'LIKE', '%' . $term . '%')->get();

        return view('search.index', [
            'tasks' => $tasks
        ]);
    }

    /**
     * Display edit page for single task.
     *
     * @param string $id
     * @return View|Factory
     * @throws InvalidArgumentException
     * @throws ModelNotFoundException
     * @throws BindingResolutionException
     */
    public function show(string $id): View|Factory
    {
        $task = Auth::user()->tasks()->where('tasks.id', Hashids::decode($id))->firstOrFail();

        return view('task.show', [
            'task' => $task,
            'lists' => Auth::user()->lists
        ]);
    }

    /**
     * Update task variables in database.
     *
     * @param string $id
     * @return Redirector|RedirectResponse|never
     * @throws ModelNotFoundException
     * @throws HttpException
     * @throws NotFoundHttpException
     * @throws BindingResolutionException
     * @throws BadRequestException
     * @throws MassAssignmentException
     * @throws InvalidArgumentException
     * @throws InvalidCastException
     * @throws LazyLoadingViolationException
     * @throws LogicException
     */
    public function patch(string $id): Redirector|RedirectResponse|HttpResponse
    {
        // Get task with uuid $id and user id from it's todo list.
        $task = Auth::user()->tasks()->where('tasks.id', Hashids::decode($id))->firstOrFail(['tasks.*', 'todo_lists.user_id']);

        if ($task === null) {
            return abort(404);
        }

        // Mark task as completed or uncompleted
        if (request()->has('function') && request()->get('function') === "complete") {
            $data = request()->validate([
                'completed' => ['string']
            ]);

            $task->update(['completed' => isset($data['completed']) ? true : false]);

            return response(status: 200);
        } else if (request()->has('function') && request()->get('function') === "edit") {
            if (request()->has('list')) {
                request()->merge([
                    'list' => strval(Hashids::decode(request()->get('list'))[0])
                ]);
            }

            $data = request()->validate([
                'title' => ['required', 'string', 'max:255'],
                'description' => ['string', 'nullable'],
                'deadline' => ['date', 'nullable'],
                'list' => [
                    'required', 'string', 'max:5',
                    // Check if list exists in database and if current user owns it.
                    Rule::exists('todo_lists', 'id')->where(function ($query) {
                        return $query->where('user_id', Auth::user()->id);
                    })
                ]
            ]);

            $task->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'deadline' => $data['deadline'],
                'list_id' => $data['list']
            ]);

            return redirect()->back();
        } else {
            return abort(400);
        }

        return abort(400);
    }

    /**
     * Delete single task
     *
     * @param string $id
     * @return Redirector|RedirectResponse
     * @throws InvalidArgumentException
     * @throws ModelNotFoundException
     * @throws LogicException
     * @throws BindingResolutionException
     */
    public function delete(string $id): Redirector|RedirectResponse
    {
        $task = Auth::user()->tasks()->where('tasks.id', Hashids::decode($id))->firstOrFail();

        $task->delete();

        return redirect()->back();
    }
}
