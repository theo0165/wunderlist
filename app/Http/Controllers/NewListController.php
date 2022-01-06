<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class NewListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(): View|Factory
    {
        return view('newList.show');
    }

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
