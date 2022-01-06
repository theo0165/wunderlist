<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\InvalidCastException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use InvalidArgumentException;

class NewListController extends Controller
{
    /** @return void  */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display create new list page.
     *
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function show(): View|Factory
    {
        return view('newList.show');
    }

    /**
     * Create new list from form data and redirect to newly created list
     *
     * @return Redirector|RedirectResponse
     * @throws BindingResolutionException
     * @throws InvalidArgumentException
     * @throws InvalidCastException
     */
    public function store(): Redirector|RedirectResponse
    {
        $data = request()->validate([
            'title' => ['string', 'max:255']
        ]);

        $list = new TodoList($data);

        $list->save();

        return redirect("/list/" . $list->uuid);
    }
}
