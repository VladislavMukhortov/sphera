<?php

namespace App\Http\Requests\Api;

use App\Models\{GoalOption, Goal};
use Illuminate\Validation\Rule;

class StoreGoalRequest extends BaseRequest
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
            'title'          => ['required', 'string'],
            'skill_id'       => ['required', 'exists:skills,id'],
            'type'           => ['required', Rule::in(Goal::TYPES)],
            'start_at'       => ['required', 'date'],
            'deadline_at'    => ['required', 'date'],
            'action_button'  => ['required_if:type,repeat', 'string', 'max:20', 'exclude'],
            'unit'           => ['required_if:type,repeat', Rule::in(GoalOption::REPEAT_TYPES), 'exclude'],
            'target_count'   => ['required_if:type,repeat', 'integer', 'exclude'],
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
            'title'             => __('validation.attributes.title'),
            'skill_id'          => __('validation.attributes.skill_id'),
            'type'              => __('validation.attributes.type'),
            'start_at'          => __('validation.attributes.start_at'),
            'deadline_at'       => __('validation.attributes.deadline_at'),
            'action_button'     => __('validation.attributes.action_button'),
            'unit'              => __('validation.attributes.unit'),
            'target_count'      => __('validation.attributes.target_count'),
        ];
    }
}
