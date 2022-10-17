<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

class RemoveMentoringRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->goal->user_id == $this->user()->id ||
            ($this->goal->user->uuid == $this->user_uuid && $this->goal->mentor_id == $this->user()->id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'user_uuid' => [Rule::requiredIf($this->goal->user_id != $this->user()->id), 'exists:users,uuid'],
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
            'user_uuid' => __('validation.attributes.user_uuid'),
        ];
    }
}
