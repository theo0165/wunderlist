<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TodoList;
use App\Models\User;
use DB;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use LogicException;
use Storage;

class ProfileController extends Controller
{
    /** @return void  */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display profile page
     *
     * @return View|Factory
     * @throws QueryException
     * @throws BindingResolutionException
     */
    public function show(): View|Factory
    {
        // This function will count completed tasks, uncompleted tasks and number of lists all in one query, amazing
        $userId = Auth::user()->id;
        $stats = (array) DB::select(
            DB::raw("SELECT
            count(case completed when '0' then 1 else null end) AS 'uncompleted',
            count(case completed when '1' then 1 else null end) AS 'completed',
            count(DISTINCT todo_lists.id) as 'lists'
        FROM tasks
        INNER JOIN todo_lists ON todo_lists.id = tasks.list_id
        WHERE todo_lists.user_id = $userId;")
        )[0];

        return view('profile.show', [
            'user' => Auth::user(),
            'stats' => [
                'lists' => $stats['lists'],
                'completed' => $stats['completed'],
                'uncompleted' => $stats['uncompleted']
            ]
        ]);
    }

    /**
     * Update profile variables in database from form data.
     *
     * @return Redirector|RedirectResponse
     * @throws BindingResolutionException
     * @throws MassAssignmentException
     * @throws InvalidArgumentException
     */
    public function patch(): Redirector|RedirectResponse
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

                if (Auth::user()->profile_picture != null) {
                    // Delete old profile picture
                    Storage::delete(str_replace('/storage', '/public', Auth::user()->profile_picture));
                }

                Auth::user()->update($data);
            }

            Auth::user()->update($data);

            return redirect("/profile")->with('success', 'The profile was successfully updated.');
        } else if (request()->has('password_update')) {
            $data = request()->validate([
                'oldpassword' => ['required', 'string', 'min:8', 'current_password'],
                'newpassword' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            Auth::user()->update(['password' => Hash::make($data['newpassword'])]);

            return redirect("/profile")->with('success', 'The password was successfully updated.');
        } else {
            return redirect("/profile")->with('error', 'Something went wrong, please try again later.');
        }
    }

    /**
     * Delete current user along with all their lists and tasks.
     *
     * @return Redirector|RedirectResponse
     * @throws LogicException
     * @throws BindingResolutionException
     */
    public function delete(): Redirector|RedirectResponse
    {
        $user = Auth::user();

        foreach ($user->tasks as $task) {
            $task->delete();
        }

        foreach ($user->lists as $list) {
            $list->delete();
        }

        $user->delete();

        return redirect("/");
    }
}
