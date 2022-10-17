<?php

namespace App\Http\Requests\Api;

class StoreGoalRepeatRequest extends BaseRequest
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
            'count' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $max_count = $this->goal->option->target_count - $this->goal->repeats()->sum('count');
                    if ($value > $max_count) {
                        $fail('The ' . $attribute . ' must be less than or equal to ' . $max_count);
                    }
                }
            ]
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
            'count' => __('validation.attributes.count'),
        ];
    }
}
