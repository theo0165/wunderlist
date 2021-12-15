<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\NewListController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Auth::routes();

Route::get('/logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.patch');

Route::get('/list/new', [NewListController::class, 'show'])->name('newList.show');
Route::post('/list/new', [NewListController::class, 'store'])->name('newList.store');

Route::get('/list/{id}', [ListController::class, 'show'])->name('list.show');
Route::get('/task/{id}', [TaskController::class, 'show'])->name('task.show');

// Auth routes
Route::get('/register', [RegisterController::class, 'show'])->name('register.show');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::get('/login', [LoginController::class, 'show'])->name('login.show');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

Route::post('/logout', [LogoutController::class, 'post'])->name('logout');
