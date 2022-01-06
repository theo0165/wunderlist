<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class LogoutController extends Controller
{
    /**
     * @return Redirector|RedirectResponse
     * @throws RuntimeException
     * @throws Exception
     * @throws BindingResolutionException
     */
    public function post(): Redirector|RedirectResponse
    {
        Auth::logout();
        return redirect("/");
    }
}
