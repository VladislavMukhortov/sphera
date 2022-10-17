<?php

namespace App\Http\Requests\Api;

use App\Models\Post;
use Illuminate\Validation\Rule;

class GetPostRequest extends BaseRequest
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
            'from_date' => ['required_with:to_date', 'date'],
            'to_date'   => ['required_with:from_date', 'date'],
            'uuid'      => ['filled', 'prohibited_if:only_own,true', 'string', 'size:36', 'exists:users,uuid'],
            'per_page'  => ['filled', 'integer'],
            'search'    => ['filled', 'string', 'max:255'],
            'type'      => ['filled', Rule::in(Post::TYPES)],
            'only_own'  => ['filled', 'in:true']
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
            'from_date'      => __('validation.attributes.from_date'),
            'to_date'        => __('validation.attributes.to_date'),
            'uuid'           => __('validation.attributes.uuid'),
            'per_page'       => __('validation.attributes.per_page'),
            'search'         => __('validation.attributes.search'),
            'type'           => __('validation.attributes.type'),
            'only_own'       => __('validation.attributes.only_own'),
        ];
    }
}
