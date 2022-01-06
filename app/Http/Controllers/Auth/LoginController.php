<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class LoginController extends Controller
{
    public function show(): View|Factory
    {
        return view('auth.login');
    }

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
