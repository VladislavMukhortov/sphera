<?php

namespace App\Http\Requests\Api\Profile;

use App\Http\Requests\Api\BaseRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserFamilyRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->family->user_id == Auth::guard('sanctum')->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'relative_uuid'  => ['filled', 'string', 'max:36', 'exists:users,uuid'],
            'is_child'       => ['filled', 'integer', 'boolean'],
            'full_name'      => ['filled', 'string', 'max:255'],
            'position'       => ['filled', 'string', 'max:255'],
            'since'          => ['filled', 'date'],
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
            'full_name'     => __('validation.attributes.full_name'),
            'position'      => __('validation.attributes.position'),
            'since'         => __('validation.attributes.since'),
        ];
    }
}
