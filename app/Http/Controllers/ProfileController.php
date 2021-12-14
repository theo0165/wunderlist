<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        //dd(Auth::user()->name);
        return view('profile.show', [
            'user' => Auth::user()
        ]);
    }

    public function update()
    {
        if (request()->has('user_update')) {
            $data = request()->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::user()->id], //Ignore unique check for current user
                'profile_picture' => ['image']
            ]);

            if (request()->has('profile_picture')) {
                $imagePath = request('profile_picture')->store('uploads/profile', 'public');

                $data['profile_picture'] = "/storage/" . $imagePath;

                Auth::user()->update($data);
            }

            Auth::user()->update($data);

            return redirect("/profile");
        } else if (request()->has('password_update')) {
            dd("Password Update");
        } else {
            dd("No form");
        }
    }
}
