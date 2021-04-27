<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;


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

Route::get('/', function () {
    return view('layouts.app');
})->name('main');

//Route::get('/google39e5616ff1a55b3d.html', function () {
//    return view('google');
//});

Auth::routes();

Route::get('/login/google', [LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [LoginController::class, 'handleGoogleCallback']);

Route::group(['middleware' => 'auth'], function() {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/login/google/classroom', [HomeController::class, 'redirectToGoogleClassroom'])->name('login.google.classroom');
    Route::get('/login/google/classroom/callback', [HomeController::class, 'handleGoogleClassroomCallback']);

    Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function() {
        Route::get('/teachers', [UserController::class, 'index'])->name('admin.teachers');
        Route::put('/block/{teacher}', [AdminController::class, 'blockOrUnblockTeacher']);
        Route::put('/unblock/{teacher}', [AdminController::class, 'blockOrUnblockTeacher']);

        Route::get('/email/list', [AdminController::class, 'index'])->name('admin.emails');
        Route::post('/email', [AdminController::class, 'creteEmail']);
        Route::put('/email/{email}', [AdminController::class, 'updateEmail']);
        Route::delete('/email/{email}', [AdminController::class, 'destroy']);
    });
});

