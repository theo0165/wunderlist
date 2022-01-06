<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\InvalidCastException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use RuntimeException;

class RegisterController extends Controller
{
    /**
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function show(): View|Factory
    {
        return view('auth.register');
    }

    /**
     * @return Redirector|RedirectResponse
     * @throws BindingResolutionException
     * @throws InvalidArgumentException
     * @throws InvalidCastException
     * @throws RuntimeException
     */
    public function store(): Redirector|RedirectResponse
    {
        $data = request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);

        $user->save();

        Auth::loginUsingId($user->id);

        return redirect("/");
    }
}
