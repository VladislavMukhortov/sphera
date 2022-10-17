<?php

namespace App\Http\Requests\Api;

class UpdateAchievementRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->achievement->user_id === $this->user()->id && $this->achievement->auto == false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title'         => ['filled', 'string'],
            'skill_id'      => ['filled', 'exists:skills,id'],
            'date'          => ['filled', 'date'],
            'description'   => ['filled', 'string'],
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
            'title'       => __('validation.attributes.title'),
            'skill_id'    => __('validation.attributes.skill_id'),
            'date'        => __('validation.attributes.date'),
            'description' => __('validation.attributes.description'),
        ];
    }
}
