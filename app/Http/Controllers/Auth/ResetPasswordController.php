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
     * Display reset password page.
     *
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
     * Reset user password if token is valid and password is confirmed.
     *
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

        // Get reset token data from database, email, token and date.
        $resetData = DB::table('password_resets')->where('token', '=', $data['token'])->first();

        // Get user with email corresponding to email in reset data.
        $user = User::where('email', $resetData->email)->first();

        $user->update([
            'password' => Hash::make($data['password'])
        ]);

        // Delete token from database so it can't be used again.
        DB::table('password_resets')->where('token', '=', $data['token'])->delete();

        return redirect('/login')->with('password_reset', 'Password has been reset!');
    }
}
