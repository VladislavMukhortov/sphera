<?php

namespace App\Http\Requests\Api\Profile;

use App\Http\Requests\Api\BaseRequest;

class StoreUserEducationRequest extends BaseRequest
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
            'university'  => ['required', 'string', 'max:255'],
            'speciality'  => ['required', 'string', 'max:255'],
            'document'    => ['filled', 'file', 'max:10000', 'exclude'],
            'date_start'  => ['required', 'date'],
            'date_end'    => ['filled', 'date', 'after:date_start'],
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
            'university'  => __('validation.attributes.university'),
            'speciality'  => __('validation.attributes.speciality'),
            'document'    => __('validation.attributes.document'),
            'date_start'  => __('validation.attributes.date_start'),
            'date_end'    => __('validation.attributes.date_end'),
        ];
    }
}
