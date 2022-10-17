<?php

namespace App\Http\Requests\Api;

use App\Models\Goal;

class StoreCommentRequest extends BaseRequest
{
    /**
     * Проверяем: 1. Существование цели, 2. Её настройки приватности, 3. Менторство 4. Владельца
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if ($this->has('goal_id')) {
            return Goal::firstWhere('id', $this->goal_id)?->user->settings()
                ->whereSetting('goal_comment')->whereValue('all')
                ->orWhere(
                    fn($q) => $q->whereSetting('goal_comment')->whereValue('mentors')->where(
                        fn($q) => $q->whereExists(fn($q) => $q->select('id')
                            ->from('goals')
                            ->where('mentor_id', $this->user()->id)
                            ->where('user_id',
                                fn($q) => $q->select('user_id')
                                    ->from('goals')
                                    ->whereId($this->goal_id)
                            )
                        )
                    )
                )
                ->orWhereExists(fn($q) => $q->select('id')->from('goals')->whereId($this->goal_id)
                    ->where('user_id', $this->user()->id))
                ->exists() ?? false;
        } else {
            return true;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'body' => ['required', 'string'],
            'goal_id' => ['required_without:report_id', 'prohibits:report_id', 'int', 'exists:goals,id'],
            'report_id' => ['required_without:goal_id', 'prohibits:goal_id', 'int', 'exists:reports,id'],
            'parent_id' => ['filled', 'int', 'exists:comments,id'],
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
            'body' => __('validation.attributes.body'),
            'goal_id' => __('validation.attributes.goal_id'),
            'report_id' => __('validation.attributes.report_id'),
            'parent_id' => __('validation.attributes.comment_id'),
        ];
    }
}
