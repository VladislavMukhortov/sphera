<?php

namespace App\Http\Controllers\Api\v1\Profile;

use App\Http\Requests\Api\DeviceLogoutRequest;
use App\Http\Requests\Api\UpdateUserNotificationRequest;
use App\Services\FileService;
use App\Http\Resources\{Profile\AuthResource, UserNotificationsCollection};
use App\Models\{Country, Profile\UserSetting, TempCode, UserNotification};
use Illuminate\Http\{Response, Request};
use Illuminate\Support\Facades\{Auth, Storage};
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\{UpdateLoginRequest, UpdateUserSettingsRequest};

class UserSettingsController extends Controller
{
    /**
     * Сервисный слой для работы с файлами
     *
     * @var FileService
     */
    public FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * Получить настройки своего аккаунта
     *
     * @return AuthResource
     */
    public function index(): AuthResource
    {
        return new AuthResource(
            Auth::guard('sanctum')->user()->load('settings', 'signins')
        );
    }

    /**
     * Обновить настройки аккаунта
     *
     * @param UpdateUserSettingsRequest $request
     *
     * @return AuthResource
     */
    public function update(UpdateUserSettingsRequest $request): AuthResource
    {
        $request->whenHas('country_id', fn($key) => $request->user()->update(['lang' => Country::find($request->country_id)->default_lang]));
        $settings = $request->notifications
            ? $request->validated() + ['notifications' => '/' . str_replace(',', '/', $request->notifications) . '/']
            : $request->validated();

        if ($request->hasFile('photo')) {
            Storage::disk('public')->delete($request->user()->photo);
            $settings['photo'] = $this->fileService->saveImage($request->photo);
        }

        foreach ($settings as $setting => $value) {
            in_array($setting, UserSetting::MAIN)
                ? $request->user()->update([$setting => $value])
                : $request->user()->settings()->updateOrCreate(['setting' => $setting], ['value' => $value]);
        }

        return new AuthResource($request->user()->load('settings', 'signins'));
    }

    /**
     * Удалить токен для соответствующего устройства
     *
     * @param DeviceLogoutRequest $request
     *
     * @return Response
     */
    public function deviceLogout(DeviceLogoutRequest $request): Response
    {
        return $request->user()->tokens()->where('abilities->user_agent', $request->user_agent)->delete()
            ? response([
                'status' => true,
                'message' => 'Device logout successfully'
            ])
            : response([
                'status' => false,
                'error' => 'Device not found',
                'errors' => (object)[]
            ]);
    }

    /**
     * Обновить почту/номер телефона
     *
     * @param UpdateLoginRequest $request
     * @param TempCode $tempCode
     *
     * @return AuthResource
     */
    public function updateLogin(UpdateLoginRequest $request, TempCode $tempCode): AuthResource
    {
        $type = email_or_phone($request->login);
        $login_old = $request->user()->$type;

        $request->user()->update([$type => $request->login]);
        $tempCode->whereIn('login', [$request->login, $login_old])->delete();

        return new AuthResource($request->user()->load('settings', 'signins'));
    }

    /**
     * Возвращает уведомления пользователя
     *
     * @param Request $request
     *
     * @return UserNotificationsCollection
     */
    public function notifications(Request $request): UserNotificationsCollection
    {
        return new UserNotificationsCollection($request->user()->notifications);
    }

    /**
     * Обновить статус уведомления
     *
     * @param UpdateUserNotificationRequest $request
     * @param UserNotification $userNotification
     *
     * @return Response
     */
    public function updateNotification(UpdateUserNotificationRequest $request, UserNotification $userNotification): Response
    {
        return response([
            'status' => $userNotification->update($request->validated()),
            'message' => 'Notifications successfully updated',
        ]);
    }
}
