<?php

namespace App\Http\Requests\Api;

class FollowRequest extends BaseRequest
{
    /**
     * Проверяем настройки приватности, кому разрешено подписываться на пользователя.
     * Запрещаем подписку на самого себя и повторную подписку
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user->id !== $this->user()->id && !$this->user->followers->contains($this->user())
            && ($this->user->settings()->whereSetting('subscribe')->whereValue('all')->exists()
                || ($this->user->settings()->whereSetting('subscribe')->whereValue('mentors')->exists()
                    && $this->user->mentors->contains($this->user())));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
