<?php

namespace App\Http\Requests\Api\Profile;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\BaseRequest;

class UpdateUserCareerRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->career->user_id == Auth::guard('sanctum')->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_name'  => ['filled', 'string', 'max:255'],
            'position_name' => ['filled', 'string', 'max:255'],
            'date_start'    => ['filled', 'date'],
            'date_end'      => ['filled', 'date'],
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
            'company_name'  => __('validation.attributes.company_name'),
            'position_name' => __('validation.attributes.position_name'),
            'date_start'    => __('validation.attributes.date_start'),
            'date_end'      => __('validation.attributes.date_end'),
        ];
    }
}
