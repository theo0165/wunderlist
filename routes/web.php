<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\NewListController;
use App\Http\Controllers\NewTaskController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TodayController;
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
Route::patch('/profile/edit', [ProfileController::class, 'patch'])->name('profile.patch');
Route::get('/profile/delete', [ProfileController::class, 'delete'])->name('profile.delete');

Route::get('/list/new', [NewListController::class, 'show'])->name('newList.show'); // Show page COMPLETED
Route::post('/list/new', [NewListController::class, 'store'])->name('newList.store'); // Add new list COMPLETED
Route::get('/list/{id}', [ListController::class, 'show'])->name('list.show'); // Show specific list COMPLETED
Route::get('/list/{id}/edit', [ListController::class, 'edit'])->name('list.edit'); // Show edit page COMPLETED
Route::patch('/list/{id}/edit', [ListController::class, 'patch'])->name('list.patch'); // Edit title of list COMPLETED
Route::get('/list/{id}/delete', [ListController::class, 'delete'])->name('list.delete'); // Delete list COMPLETED
Route::get('/list/{id}/markall', [ListController::class, 'markall'])->name('list.markall'); // Mark all tasks in list

Route::get('/task/new', [NewTaskController::class, 'show'])->name('newTask.show'); // Create new task page COMPLETED
Route::post('/task/new', [NewTaskController::class, 'store'])->name('newTask.store'); // Create new task COMPLETED
Route::get('/task/{id}/edit', [TaskController::class, 'show'])->name('task.show'); // Show edit page COMPLETE
Route::post('/task/{id}/edit', [TaskController::class, 'patch'])->name('task.patch'); // Edit variables of task COMPLETE
Route::get('/task/{id}/delete', [TaskController::class, 'delete'])->name('task.delete'); // Delete task COMPLETED
Route::get('/task/all', [TaskController::class, 'index'])->name('task.index'); // Show all tasks COMPLETED
Route::get('/task/search', [TaskController::class, 'search'])->name('search.show'); // Show tasks based on search

Route::get('/today', [TodayController::class, 'index'])->name('today.index'); // Show tasks with deadline today COMPLETE

// Auth routes
Route::get('/register', [RegisterController::class, 'show'])->name('register.show');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::get('/login', [LoginController::class, 'show'])->name('login.show');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

Route::post('/logout', [LogoutController::class, 'post'])->name('logout');

Route::get('/forgot_password', [ForgotPasswordController::class, 'show'])->name('forgotPassword.show');
Route::post('/forgot_password/send', [ForgotPasswordController::class, 'send'])->name('forgotPassword.send');

Route::get('/reset_password/{token}', [ResetPasswordController::class, 'show'])->name('password.show');
Route::post('/reset_password', [ResetPasswordController::class, 'update'])->name('password.update');
