<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UpdateSettingRequest;
use App\Http\Resources\BaseResource;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

use function view;

class SettingController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Setting::class);
    }

    /**
     * Возвращает список параметров сервиса
     *
     * @return View
     *
     */
    public function index(): View
    {
        return view('settings.index');
    }

    /**
     * Возвращает страницу для редактирования параметра
     *
     * @param Setting $setting
     *
     * @return View
     */
    public function edit(Setting $setting): View
    {
        return view('settings.edit', compact('setting'));
    }

    /**
     * Обновление параметра
     *
     * @param UpdateSettingRequest $request
     * @param Setting $setting
     *
     * @return RedirectResponse
     */
    public function update(UpdateSettingRequest $request, Setting $setting): RedirectResponse
    {
        $setting->update($request->validated());

        return redirect()->route('settings.index')->with('success', __('message.mission_complete'));
    }

    /**
     * Получить дефолтные данные сервиса по стоимости подписки
     *
     * @return BaseResource
     */
    public function getSettings(): BaseResource
    {
        return new BaseResource(Setting::firstWhere('parameter', Setting::FOLLOW_AMOUNT)->only(['parameter', 'value']));
    }
}
