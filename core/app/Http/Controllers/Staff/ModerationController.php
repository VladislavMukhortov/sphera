<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

use function __;
use function redirect;
use function view;

class ModerationController extends Controller
{
    /**
     *
     * Возвращает страницу с доступными, для модерации, категориями
     *
     * @return View
     *
     */
    public function index(): View
    {
        $skills_count = Skill::where('is_custom', 1)->where('is_allowed', 0)->count();

        return view('moderation.index', compact('skills_count'));
    }

    /**
     *
     * Возвращает страницу с новыми пользовательскими навыками для рассмотрения
     *
     * @return View
     *
     */
    public function skills(): View
    {
        return view('moderation.skills');
    }

    /**
     * Отклонение пользовательского навыка
     *
     * @param Skill $skill
     *
     * @return RedirectResponse
     */
    public function skillDecline(Skill $skill): RedirectResponse
    {
        $skill->delete();

        return redirect()->route('moderation.skills')->with('success', __('message.skill_declined'));
    }

    /**
     * Принятие пользовательского навыка
     *
     * @param Skill $skill
     *
     * @return RedirectResponse
     */
    public function skillAccept(Skill $skill): RedirectResponse
    {
        $skill->update([
            'is_allowed' => 1,
        ]);

        return redirect()->route('moderation.skills')->with('success', __('message.skill_accepted'));
    }
}
