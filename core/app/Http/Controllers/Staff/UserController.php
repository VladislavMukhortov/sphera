<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Confirmation\CheckPinRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

use function view;

class UserController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    /**
     * Возвращает список пользователей
     *
     * @return View
     *
     */
    public function index(): View
    {
        return view('users.index');
    }

    /**
     * Возвращает страницу пользователя
     *
     * @param  User $user
     * @return View
     */
    public function show(User $user): View
    {
        $user->load([])
            ->loadCount([]);
        return view('users.show', compact('user'));
    }

    /**
     * Блокировать пользователя
     *
     * @param CheckPinRequest $request
     * @param User $user
     * @return RedirectResponse
     */
    public function block(CheckPinRequest $request, User $user): RedirectResponse
    {
        $user->update(['is_banned' => 1]);

        return redirect()->route('users.show', $user)->with('success', __('message.mission_complete'));
    }

    /**
     * Разблокировать пользователя
     *
     * @param CheckPinRequest $request
     * @param User $user
     * @return RedirectResponse
     */
    public function unblock(CheckPinRequest $request, User $user): RedirectResponse
    {
        $user->update(['is_banned' => 0]);

        return redirect()->route('users.show', $user)->with('success', __('message.mission_complete'));
    }
}
