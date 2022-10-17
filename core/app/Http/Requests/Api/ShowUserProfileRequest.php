<?php

namespace App\Http\Requests\Api;

class ShowUserProfileRequest extends BaseRequest
{
    /**
     * Пропускаем на свой профиль или проверяем настройки приватности.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->id === $this->user->id
            || ($this->user->settings()->whereSetting('profile_visibility')->whereValue('all')->exists()
                || ($this->user->settings()->whereSetting('profile_visibility')->whereValue('mentors')->exists()
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
