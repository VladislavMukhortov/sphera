<?php

use App\Http\Controllers\Auth\{FacebookAuthController, GoogleAuthController};
//use App\Http\Resources\CountriesCollection;
use App\Models\Country;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\{AchievementController,
    AuthController,
    BalanceController,
    CommentController,
    FirebaseController,
    GoalController,
    GoalRepeatController,
    GlobalSearchController,
    LocationController,
    ReportController,
    TaskController,
    PostController,
    UserController,
    Profile\UserCareerController,
    Profile\UserEducationController,
    Profile\UserSkillController,
    Profile\UserFamilyController,
    Profile\UserSettingsController,
    FeedbackController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * Auth
 */
Route::post('code', [AuthController::class, 'code']);
Route::post('auth', [AuthController::class, 'auth'])->middleware('locale');
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

/** Получить списки стран, городов */
Route::middleware('locale')->group(function () {
    Route::get('countries', [LocationController::class, 'getCountryList'])->name('countries');
    Route::get('cities', [LocationController::class, 'getCityList'])->name('cities');
});

/** Авторизация через сторонние сервисы */
Route::group(['prefix' => 'auth', 'middleware' => ['web']], function () {
    Route::get('/google', [GoogleAuthController::class, 'redirectToProvider'])->name('auth.google');
    Route::get('/google/callback', [GoogleAuthController::class, 'handleProviderCallback'])->name('auth.google.callback');
    Route::get('/facebook', [FacebookAuthController::class, 'redirectToProvider'])->name('auth.facebook');
    Route::get('/facebook/callback', [FacebookAuthController::class, 'handleProviderCallback'])->name('auth.facebook.callback');
});

Route::middleware(['auth:sanctum'])->group(function () {
    /** Получить профиль пользователя */
    Route::get('/user/{user:uuid}', [UserController::class, 'getUserProfile'])->name('user.getProfile');

    /** Профиль */
    Route::group([
        'prefix' => 'profile'
    ], function () {
        /** Поиск пользователя по почтовому адресу/телефону (для добавления в качестве родственника) */
        Route::get('/search', [UserController::class, 'search'])->name('user.search');

        /** Работа, образование и семья пользователя */
        Route::apiResource('career', UserCareerController::class)->except('show');
        Route::apiResource('education', UserEducationController::class)->except('show', 'update');
        Route::as('education.')->post('education/{education:id}', [UserEducationController::class, 'update'])->name('update');
//        Route::apiResource('family', UserFamilyController::class)->except('show'); //пока не нужно, отключено

        /** Пользовательские настройки */
        Route::group([
            'prefix' => 'settings',
            'as' => 'profile.settings.'
        ], function () {
            Route::get('/', [UserSettingsController::class, 'index'])->name('index');
            Route::post('/', [UserSettingsController::class, 'update'])->name('update');
            Route::post('/device_logout', [UserSettingsController::class, 'deviceLogout'])->name('deviceLogout');
            Route::post('/update_login', [UserSettingsController::class, 'updateLogin'])->name('updateLogin');
            Route::get('/notifications', [UserSettingsController::class, 'notifications'])->name('notifications');
        });

        Route::middleware('locale')->prefix('skills')->as('userSkills.')->group(function () {
            Route::get('/list', [UserSkillController::class, 'list'])->name('list');
            Route::get('/', [UserSkillController::class, 'index'])->name('index');
            Route::post('/mentor', [UserSkillController::class, 'storeMentorSkill'])->name('storeMentorSkill');
            Route::post('/hobby', [UserSkillController::class, 'storeUserHobby'])->name('storeUserHobby');
            Route::delete('/{user_skill:id}', [UserSkillController::class, 'destroy'])->name('destroy');
        });
    });

    /** Лента */
    Route::group([
        'prefix' => 'posts',
        'as' => 'posts.'
    ], function () {
//        Route::get('/list', [PostController::class, 'list'])->name('list'); //пока отключен
        Route::get('/follows', [PostController::class, 'follows'])->name('follows');
        Route::get('/common', [PostController::class, 'common'])->name('common');
        Route::get('/favorites', [PostController::class, 'favorites'])->name('favorites');
        Route::post('/favorites/{post:id}', [PostController::class, 'favoriteAdd'])->name('store.favorite');
        Route::delete('/favorites/{post:id}', [PostController::class, 'favoriteRemove'])->name('remove.favorite');

        Route::post('/{goal:id}', [PostController::class, 'store'])->name('store');
        Route::put('/{post:id}', [PostController::class, 'update'])->name('update');
        Route::delete('/{post:id}', [PostController::class, 'destroy'])->name('destroy');
    });

    /** Цели */
    Route::apiResource('goals', GoalController::class);
    Route::apiResource('goals/{goal:id}/tasks', TaskController::class);
    Route::apiResource('goals/{goal:id}/repeats', GoalRepeatController::class)->except('show');

    /** Комментарии */
    Route::apiResource('comments', CommentController::class);

    /** Достижения */
    Route::apiResource('achievements', AchievementController::class);


    /** Отчеты */
    Route::apiResource('reports', ReportController::class)->except('update');
    Route::as('reports.')->post('reports/{report:id}', [ReportController::class, 'update'])->name('update');

    /** Менторство */
    Route::group([
        'prefix' => 'mentoring',
    ], function () {
        Route::get('/offers', [UserController::class, 'offers'])->name('mentoring.offers');
        Route::post('/{goal:id}/send_offer', [UserController::class, 'sendOffer'])->name('mentoring.sendOffer');
        Route::post('/mentors/{offer:id}/add', [UserController::class, 'addMentor'])->name('mentors.add');
        Route::post('/students/{offer:id}/add', [UserController::class, 'addStudent'])->name('students.add');
        Route::post('/{offer:id}/decline_offer', [UserController::class, 'declineOffer'])->name('mentoring.declineOffer');
        Route::get('/mentors', [UserController::class, 'mentorList'])->name('mentors.list');
        Route::get('/students', [UserController::class, 'studentList'])->name('students.list');
        Route::delete('/mentors/{goal:id}/remove', [UserController::class, 'removeMentor'])->name('mentors.remove');
        Route::delete('/students/{goal:id}/remove', [UserController::class, 'removeStudent'])->name('students.remove');
    });

    /** Подписки & подписчики */
    Route::get('/followers', [UserController::class, 'getFollowers'])->name('getFollowers');
    Route::get('/follows', [UserController::class, 'getFollows'])->name('getFollows');
    Route::post('/{user:uuid}/follow', [UserController::class, 'follow'])->name('follow');
    Route::post('/{user:uuid}/unfollow', [UserController::class, 'unfollow'])->name('unfollow');

    /** Firebase токены */
    Route::group([
        'prefix' => 'firebase'
    ], function () {
        Route::get('/show', [FirebaseController::class, 'show'])->name('firebase.show');
        Route::post('/store', [FirebaseController::class, 'store'])->name('firebase.store');
    });

    /** Баланс */
    Route::group([
        'prefix' => 'balance',
    ], function () {
        Route::get('/', [BalanceController::class, 'balance'])->name('balance');
    });

    /** Глобальный поиск */
    Route::group([
        'prefix' => 'search',
        'as' => 'search.',
    ], function () {
        Route::get('/users', [GlobalSearchController::class, 'users'])->name('users');
        Route::get('/goals', [GlobalSearchController::class, 'goals'])->name('goals');
        Route::get('/posts', [GlobalSearchController::class, 'posts'])->name('posts');
        Route::get('/my_posts', [GlobalSearchController::class, 'myPosts'])->name('myPosts');
        Route::get('/achievements', [GlobalSearchController::class, 'achievement'])->name('achievement');
    });

    /** Отзывы */
//    Route::resource('feedbacks', FeedbackController::class);
    Route::group([
        'prefix' => 'feedbacks',
    ], function () {
        Route::get('/list', [FeedbackController::class, 'index'])->name('feedbacks');
        Route::get('/{feedback:id}', [FeedbackController::class, 'show'])->name('feedback-show');
        Route::post('/store', [FeedbackController::class, 'store'])->name('feedback-store');
        Route::put('/update/{feedback:id}', [FeedbackController::class, 'update'])->name('feedback-update');
        Route::delete('/delete/{feedback:id}', [FeedbackController::class, 'destroy'])->name('feedback-delete');
    });

    /** Дефолтные настройки сервиса */
//    Route::get('/settings', [SettingController::class, 'getSettings'])->name('default.amount.follow');
});
