<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Confirmation\CheckPinRequest;
use App\Http\Requests\Staff\{StoreStaffRequest, UpdateStaffRequest};
use App\Models\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

use function __;
use function redirect;
use function view;

class StaffController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Staff::class);
    }

    /**
     * Возвращает главную страницу
     *
     * @return View
     */
    public function index(): View
    {
        return view('staff.index');
    }

    /**
     * Возвращает страницу для создания админа
     *
     * @return View
     */
    public function create(): View
    {
        return view('staff.create');
    }

    /**
     * Возвращает страницу админа
     *
     * @param Staff $staff
     *
     * @return View
     */
    public function show(Staff $staff): View
    {
        $lastActions = $staff->myEssenceLogs()->with('targetable')->latest()->limit(5)->get();

        return view('staff.show', compact('staff', 'lastActions'));
    }

    /**
     * Возвращает страницу для редактирования админа
     *
     * @param Staff $staff
     *
     * @return View
     */
    public function edit(Staff $staff): View
    {
        return view('staff.edit', compact('staff'));
    }

    /**
     * Сохраняет нового админа
     *
     * @param StoreStaffRequest $request
     *
     * @return RedirectResponse
     */
    public function store(StoreStaffRequest $request): RedirectResponse
    {
        $staff = Staff::create($request->merge(['password' => Hash::make($request->password)])->all());

        return redirect()->route('staff.show', $staff)->with('success', __('message.mission_complete'));
    }

    /**
     * Обновление админа
     *
     * @param UpdateStaffRequest $request
     * @param Staff $staff
     *
     * @return RedirectResponse
     */
    public function update(UpdateStaffRequest $request, Staff $staff): RedirectResponse
    {
        $staff->update(
            $request->password
                ? $request->merge(['password' => Hash::make($request->password)])->all()
                : $request->except('password')
        );

        return redirect()->route('staff.show', $staff)->with('success', __('message.mission_complete'));
    }

    /**
     * Возвращает страницу логинов админа
     *
     * @param Staff $staff
     *
     * @return View
     */
    public function signins(Staff $staff): View
    {
        return view('staff.signins', compact('staff'));
    }

    /**
     * Блокировать админа
     *
     * @param CheckPinRequest $request
     * @param Staff $staff
     *
     * @return RedirectResponse
     */
    public function block(CheckPinRequest $request, Staff $staff): RedirectResponse
    {
        $staff->update([
            'is_enable' => 0
        ]);

        return redirect()->route('staff.show', $staff)->with('success', __('message.mission_complete'));
    }

    /**
     * Разблокировать админа
     *
     * @param CheckPinRequest $request
     * @param Staff $staff
     *
     * @return RedirectResponse
     */
    public function unblock(CheckPinRequest $request, Staff $staff): RedirectResponse
    {
        $staff->update([
            'is_enable' => 1
        ]);

        return redirect()->route('staff.show', $staff)->with('success', __('message.mission_complete'));
    }
}
