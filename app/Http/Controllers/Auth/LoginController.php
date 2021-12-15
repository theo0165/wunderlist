<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function store()
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
