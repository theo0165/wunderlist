<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use DB;
use Mail;
use Str;

class ForgotPasswordController extends Controller
{
    public function show(): View|Factory
    {
        return view('auth.passwords.forgot.show');
    }

    public function send(): View|Factory
    {
        $data = request()->validate([
            'email' => ['required', 'email']
        ]);

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $data['email'],
            'token' => $token,
            'created_at' => date('Y-m-d H:m:s', strtotime('now'))
        ]);

        Mail::to($data['email'])->send(new ResetPassword($token));

        return view('auth.passwords.forgot.send');
    }
}
