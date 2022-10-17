<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Confirmation\CheckPinRequest;
use App\Http\Requests\Skills\{StoreSkillRequest, UpdateSkillRequest};
use App\Models\{Skill, SkillLocale};
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

use function __;
use function redirect;
use function view;

class SkillController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Skill::class);
    }

    /**
     * Возвращает страницу с навыками
     *
     * @return View
     *
     */
    public function index(): View
    {
        return view('skills.index');
    }

    /**
     * Сохраняет новый навык
     *
     * @param StoreSkillRequest $request
     *
     * @return RedirectResponse
     */
    public function store(StoreSkillRequest $request): RedirectResponse
    {
        Skill::create($request->only('parent_id'))->locales()->saveMany([
            new SkillLocale(['lang' => 'ru', 'title' => $request->title_ru]),
            new SkillLocale(['lang' => 'en', 'title' => $request->title_en]),
            new SkillLocale(['lang' => 'cn', 'title' => $request->title_cn]),
        ]);

        return redirect()->route('skills.index')->with('success', __('message.mission_complete'));
    }

    /**
     * Обновление навыка
     *
     * @param UpdateSkillRequest $request
     * @param Skill $skill
     *
     * @return RedirectResponse
     */
    public function update(UpdateSkillRequest $request, Skill $skill): RedirectResponse
    {
        $skill->update($request->only(['parent_id', 'is_allowed']));
        $request->whenHas('title_ru', fn($q) => $skill->locales()->whereLang('ru')->update(['title' => $q]));
        $request->whenHas('title_en', fn($q) => $skill->locales()->whereLang('en')->update(['title' => $q]));
        $request->whenHas('title_cn', fn($q) => $skill->locales()->whereLang('cn')->update(['title' => $q]));

        return redirect()->route('skills.index')->with('success', __('message.mission_complete'));
    }

    /**
     * Удаление навыка
     *
     * @param CheckPinRequest     $request
     * @param Skill               $skill
     *
     * @return RedirectResponse
     */
    public function destroy(CheckPinRequest $request, Skill $skill): RedirectResponse
    {
        $skill->delete();

        return redirect()->route('skills.index')->with('success', __('message.mission_complete'));
    }

    /**
     *
     * Возвращает страницу для создания навыка
     *
     * @return View
     *
     */
    public function create(): View
    {
        $parents = Skill::all()->load('locale')
            ->sortBy('locale.title')
            ->pluck('locale.title', 'id');

        return view('skills.create', compact('parents'));
    }

    /**
     * Возвращает страницу для редактирования навыка
     *
     * @param Skill $skill
     *
     * @return View
     */
    public function edit(Skill $skill): View
    {
        $parents = Skill::with('locale')
            ->whereNotIn('id', [$skill->id])
            ->get()
            ->sortBy('locale.title')
            ->pluck('locale.title', 'id');

        return view('skills.edit', compact('skill', 'parents'));
    }
}
