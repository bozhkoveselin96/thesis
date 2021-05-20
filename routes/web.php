<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClassroomController;
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

Auth::routes();

Route::prefix('login/google')->group(function () {
    Route::get('/', [LoginController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/callback', [LoginController::class, 'handleGoogleCallback']);
});

Route::group(['middleware' => 'auth'], function() {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::prefix('login/google/classroom')->group(function () {
        Route::get('/', [HomeController::class, 'redirectToGoogleClassroom'])->name('login.google.classroom');
        Route::get('/callback', [HomeController::class, 'handleGoogleClassroomCallback']);
    });

    Route::group(['middleware' => 'connected', 'prefix' => 'classroom'], function () {
        Route::get('/courses', [ClassroomController::class, 'courses'])->name('classroom.courses');
        Route::get('/students/{courseId}', [ClassroomController::class, 'students'])->name('classroom.students');
        Route::get('/students/{courseId}/export', [ClassroomController::class, 'export'])->name('classroom.students.export');
    });

    Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function() {
        Route::prefix('teacher')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('admin.teacher.index');
            Route::put('/block/{teacher}', [AdminController::class, 'blockOrUnblockTeacher']);
            Route::put('/unblock/{teacher}', [AdminController::class, 'blockOrUnblockTeacher']);
        });

        Route::resource('email', AdminController::class);
    });
});

