<?php

use App\Http\Controllers\{Auth\LoginController, HomeController};
use App\Http\Controllers\Staff\{ModerationController,
    SettingController,
    SkillController,
    StaffController,
    UserController
};
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/** Административные маршруты */
Route::group(['middleware' => ['auth:staff']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    /** Администраторы */
    Route::resource('staff', StaffController::class);
    Route::group([
        'prefix' => 'staff',
        'as' => 'staff.'
    ], function () {
        Route::get('/{staff}/signins', [StaffController::class, 'signins'])->name('signins');
        Route::patch('/{staff}/block', [StaffController::class, 'block'])->name('block');
        Route::patch('/{staff}/unblock', [StaffController::class, 'unblock'])->name('unblock');
    });

    /** Настройки сервиса */
    Route::resource('settings', SettingController::class)->only(['index', 'update', 'edit']);

    /** Области знаний (навыки/категории/увлечения) */
    Route::resource('skills', SkillController::class)->except('show');

    /** Пользователи */
    Route::resource('users', UserController::class);
    Route::group([
        'prefix' => 'users',
        'as' => 'users.'
    ], function () {
        Route::patch('/{user}/block', [UserController::class, 'block'])->name('block');
        Route::patch('/{user}/unblock', [UserController::class, 'unblock'])->name('unblock');
    });

    /** Модерация пользовательского контента */
    Route::group([
        'prefix' => 'moderation',
        'as' => 'moderation.'
    ], function () {
        Route::get('/', [ModerationController::class, 'index'])->name('index');
        Route::get('/skills', [ModerationController::class, 'skills'])->name('skills');
        Route::post('/{skill}/accept', [ModerationController::class, 'skillAccept'])->name('skill.accept');
        Route::post('/{skill}/decline', [ModerationController::class, 'skillDecline'])->name('skill.decline');
    });
});
