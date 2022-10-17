<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'value' => ['filled', 'numeric'],
            'pin' => [
                'required',
                Rule::exists('staff')->where(fn($q) => $q->whereId($this->user()->id)->wherePin($this->pin))
            ]
        ];
    }
}
