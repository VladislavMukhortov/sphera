<?php

namespace App\Http\Requests\Skills;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSkillRequest extends FormRequest
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
            'title_ru' => ['filled', 'string', 'max:255', Rule::unique('skill_locales', 'title')->ignore($this->skill->id, 'skill_id')],
            'title_en' => ['filled', 'string', 'max:255', Rule::unique('skill_locales', 'title')->ignore($this->skill->id, 'skill_id')],
            'title_cn' => ['filled', 'string', 'max:255', Rule::unique('skill_locales', 'title')->ignore($this->skill->id, 'skill_id')],
            'parent_id' => ['nullable', 'exists:skills,id'],
            'is_allowed' => ['filled', 'boolean'],
        ];
    }
}
