<?php

namespace App\Http\Requests\Api;

use App\Models\Goal;
use Illuminate\Validation\Rule;

class UpdateGoalRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->goal->user_id == $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title'       => ['filled', 'string'],
            'skill_id'    => ['filled', 'exists:skills,id'],
            'status'      => ['filled', Rule::in(Goal::STATUSES)],
            'start_at'    => ['filled', 'date'],
            'deadline_at' => ['filled', 'date']
        ];
    }
}
