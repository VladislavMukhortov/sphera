<?php

namespace App\Http\Requests\Api\Profile;

use App\Http\Requests\Api\BaseRequest;

class StoreUserFamilyRequest extends BaseRequest
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
            'relative_uuid'  => ['required_without:full_name', 'string', 'max:36', 'exists:users,uuid'],
            'is_child'       => ['filled', 'integer', 'boolean'],
            'file'           => ['filled', 'image', 'mimes:jpg,jpeg,png,bmp', 'max:10000'],
            'full_name'      => ['required_without:relative_uuid', 'string', 'max:255'],
            'position'       => ['required_without:relative_uuid', 'string', 'max:255'],
            'since'          => ['required', 'date'],
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
            'relative_uuid' => __('validation.attributes.relative_uuid'),
            'is_child'      => __('validation.attributes.is_child'),
            'file'          => __('validation.attributes.file'),
            'full_name'     => __('validation.attributes.full_name'),
            'position'      => __('validation.attributes.position'),
            'since'         => __('validation.attributes.since'),
        ];
    }
}
