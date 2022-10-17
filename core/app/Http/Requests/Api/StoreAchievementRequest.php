<?php

namespace App\Http\Requests\Api;

class StoreAchievementRequest extends BaseRequest
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
            'title'         => ['required', 'string'],
            'skill_id'      => ['required', 'exists:skills,id'],
            'date'          => ['required', 'date'],
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
