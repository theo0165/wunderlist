<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use DB;
use Hash;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function show(string $token)
    {
        return view('auth.passwords.reset.show', [
            'token' => $token
        ]);
    }

    public function update()
    {
        $data = request()->validate([
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
            'token' => ['required', 'string', 'size:64', 'exists:password_resets']
        ]);

        $resetData = DB::table('password_resets')->where('token', '=', $data['token'])->first();

        $user = User::where('email', $resetData->email)->first();

        $user->update([
            'password' => Hash::make($data['password'])
        ]);

        DB::table('password_resets')->where('token', '=', $data['token'])->delete();

        return redirect('/login')->with('password_reset', 'Password has been reset!');
    }
}
