<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use DB;
use Hash;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use InvalidArgumentException;

class ResetPasswordController extends Controller
{
    /**
     * @param string $token
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function show(string $token): View|Factory
    {
        return view('auth.passwords.reset.show', [
            'token' => $token
        ]);
    }

    /**
     * @return Redirector|RedirectResponse
     * @throws BindingResolutionException
     * @throws InvalidArgumentException
     * @throws MassAssignmentException
     */
    public function update(): Redirector|RedirectResponse
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
