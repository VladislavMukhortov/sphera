<?php

namespace App\Http\Requests\Api;

class ForceUnfollowRequest extends BaseRequest
{
    /**
     * Проверяем есть ли в подписчиках, и запрещаем запрос на самого себя
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user->id !== $this->user()->id && $this->user()->followers->contains($this->user);
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
