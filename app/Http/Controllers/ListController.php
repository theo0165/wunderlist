<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TodoList;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use LogicException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ListController extends Controller
{
    /** @return void  */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display single list page with uuid of $id. Throw 404 if list does not exist or user is unauthorized
     *
     * @param string $id
     * @return View|Factory
     * @throws InvalidArgumentException
     * @throws ModelNotFoundException
     * @throws BindingResolutionException
     */
    public function show(string $id): View|Factory
    {
        $list = Auth::user()->lists()->where('id', Hashids::decode($id))->firstOrFail();

        return view('list.show', [
            'list' => $list,
            'tasks' => $list->tasks
        ]);
    }

    /**
     * Display edit page for single list. Throw 404 if user is unauthorized.
     *
     * @param string $id
     * @return View|Factory
     * @throws InvalidArgumentException
     * @throws ModelNotFoundException
     * @throws BindingResolutionException
     */
    public function edit(string $id): View|Factory
    {
        $list = Auth::user()->lists()->where('id', Hashids::decode($id))->firstOrFail();

        return view('list.edit', [
            'list' => $list
        ]);
    }

    /**
     * Update list in database.
     *
     * @param string $id
     * @return Redirector|RedirectResponse|never
     * @throws BindingResolutionException
     * @throws InvalidArgumentException
     * @throws ModelNotFoundException
     * @throws MassAssignmentException
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function patch(string $id): Redirector|RedirectResponse
    {
        if (request()->has('title')) {
            $data = request()->validate([
                'title' => ['string', 'max:255']
            ]);

            $list = Auth::user()->lists()->where('id', Hashids::decode($id))->firstOrFail();
            $list->update(['title' => $data['title']]);

            return redirect(route('list.show', $id));
        } else {
            return abort('400');
        }
    }

    /**
     * Delete single list.
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
        $list = Auth::user()->lists()->where('id', Hashids::decode($id))->firstOrFail();

        # Delete all tasks when deleting list.
        foreach ($list->tasks as $task) {
            $task->delete();
        }

        $list->delete();

        return redirect("/");
    }
}
