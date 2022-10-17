<?php

namespace App\Http\Requests\Api;

class UpdateReportRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->report->user_id === $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'description'   => ['filled', 'string'],
            'photo'         => ['filled', 'image', 'mimes:jpg,jpeg,png,bmp', 'max:10000'],
            'goal_id'       => ['filled', 'exists:goals,id'],
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
            'description' => __('validation.attributes.description'),
            'photo'       => __('validation.attributes.image'),
            'goal_id'     => __('validation.attributes.goal_id'),
        ];
    }
}
