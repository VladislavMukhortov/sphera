<?php

namespace App\Http\Resources\Profile;

use App\Http\Resources\BaseCollection;
use App\Models\Profile\UserSetting;

class UserNotificationSettingsCollection extends BaseCollection
{
    /**
     * Парсинг данных по настройкам уведомлений пользователя
     *
     * @param $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        $notificationIds = $this->collection->firstWhere('setting', 'notifications')->value;

        $notifications = [];
        foreach (explode('/', $notificationIds) as $item) {
            if ($item >= 0) {
                $notifications[] = [
                    'id'   => $item,
                    'name' => UserSetting::NOTIFICATIONS[$item],
                ];
            }
        }

        return $notifications;
    }
}
