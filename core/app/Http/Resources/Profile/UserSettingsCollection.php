<?php

namespace App\Http\Resources\Profile;

use App\Http\Resources\BaseCollection;

class UserSettingsCollection extends BaseCollection
{
    public $collects = UserSettingResource::class;

    /**
     * Убираем настройки уведомлений из этой коллекции
     *
     * @param $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return $this->collection->whereNotIn('setting', ['notifications'])->toArray();
    }
}
