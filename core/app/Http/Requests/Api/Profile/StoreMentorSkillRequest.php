<?php

namespace App\Http\Requests\Api\Profile;

use App\Http\Requests\Api\BaseRequest;

class StoreMentorSkillRequest extends BaseRequest
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
            'skill_id' => ['required', 'exists:skills,id'],
            'title' => [
                'filled',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($this->skill_id && $this->user()->mentorSkills()->whereSkillId($this->skill_id)->doesntExist()) {
                        $fail('The ' . $attribute . ' is prohibited. Please add only main skill first.');
                    }
                },
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'skill_id'  => __('validation.attributes.skill_id'),
            'title'     => __('validation.attributes.title'),
        ];
    }
}
