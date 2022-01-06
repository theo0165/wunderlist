<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use RuntimeException;

class LoginController extends Controller
{
    /**
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function show(): View|Factory
    {
        return view('auth.login');
    }

    /**
     * @return Redirector|RedirectResponse
     * @throws BindingResolutionException
     * @throws RuntimeException
     */
    public function store(): Redirector|RedirectResponse
    {
        $data = request()->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'remember' => ['string']
        ]);

        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']], isset($data['remember']))) {
            return redirect('/');
        } else {
            return redirect()->back()->withErrors(['invalid' => "Invalid credentials"]);
        }
    }
}
