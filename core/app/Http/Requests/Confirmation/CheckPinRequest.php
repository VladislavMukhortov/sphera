<?php

namespace App\Http\Requests\Confirmation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckPinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pin' => [
                Rule::exists('staff')->where(function ($query) {
                    return $query->whereId($this->user()->id)->wherePin($this->pin);
                })
            ]
        ];
    }

    /**
     *
     * Сообщение об ошибке
     *
     * @return string[]
     */
    public function messages()
    {
        return [
            'pin.exists' => 'Пин введён неверно'
        ];
    }
}
