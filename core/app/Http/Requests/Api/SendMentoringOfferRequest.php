<?php

namespace App\Http\Requests\Api;

class SendMentoringOfferRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->goal->user_id === $this->user()->id || $this->goal->user->uuid === $this->user_uuid;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'user_uuid' => ['required', 'exists:users,uuid'],
            'make_user' => ['required', 'in:mentor,student'],
            'amount' => [
                'filled',
                'int',
                function ($attribute, $value, $fail) {
                    if ($this->make_user == 'mentor' && $value > $this->user()->currentBalance()) {
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
            'user_uuid'       => __('validation.attributes.user_uuid'),
            'make_user'       => __('validation.attributes.make_user'),
            'amount'          => __('validation.attributes.amount'),
        ];
    }
}
