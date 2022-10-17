<?php

namespace App\Http\Requests\Skills;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSkillRequest extends FormRequest
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
            'title_ru'  => ['required', 'string', 'max:255', Rule::unique('skill_locales', 'title')],
            'title_en'  => ['required', 'string', 'max:255', Rule::unique('skill_locales', 'title')],
            'title_cn'  => ['required', 'string', 'max:255', Rule::unique('skill_locales', 'title')],
            'parent_id' => ['nullable', 'exists:skills,id'],
        ];
    }
}
