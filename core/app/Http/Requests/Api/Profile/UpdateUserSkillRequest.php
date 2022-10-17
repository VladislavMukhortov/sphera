<?php

namespace App\Http\Requests\Api\Profile;

use App\Http\Requests\Api\BaseRequest;

class UpdateUserSkillRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user_skill->user->id == $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'mentor' => [
                'filled',
                'boolean',
                function ($attribute, $value, $fail) {
                    if ($value == true && !$this->user()->is_mentor) {
                        $fail('The user is not a mentor');
                    }
                }
            ],
            'amount' => [
                'filled',
                'int',
                function ($attribute, $value, $fail) {
                    if (!$this->user()->is_mentor) {
                        $fail('The user is not a mentor');
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
            'mentor'  => __('validation.attributes.mentor'),
            'amount'  => __('validation.attributes.amount'),
        ];
    }
}
