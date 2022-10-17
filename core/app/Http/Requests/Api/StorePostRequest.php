<?php

namespace App\Http\Requests\Api;

class StorePostRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->goal->user_id === $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title'     => ['required', 'string', 'max:255'],
            'tags'      => ['filled', 'array'],
            'amount'    => [
                'filled',
                'int',
                function ($attribute, $value, $fail) {
                    if ($value > $this->user()->currentBalance()) {
                        $fail('The ' . $attribute . ' is invalid. Not enough coins.');
                    }
                }
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
            'title'     => __('validation.attributes.title'),
            'amount'    => __('validation.attributes.amount'),
            'tags'      => __('validation.attributes.tags'),
        ];
    }
}
