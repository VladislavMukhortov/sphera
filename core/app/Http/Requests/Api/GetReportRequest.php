<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use App\Models\Goal;

class GetReportRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->report->user_id === $this->user()->id
            || ($this->report->user->settings()->whereSetting('report_visibility')->whereValue('all')->exists()
                || ($this->report->user->settings()->whereSetting('report_visibility')->whereValue('mentors')->exists()
                    && $this->report->user->mentors->contains($this->user())));
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
            'type'        => ['filled', Rule::in(Goal::TYPES)],
            'status'      => ['filled', Rule::in(Goal::STATUSES)],
            'start_at'    => ['filled', 'date'],
            'deadline_at' => ['filled', 'date'],
            'from'        => ['filled', 'date'],
            'to'          => ['filled', 'date'],
            'per_page'    => ['filled', 'int']
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
            'title'         => __('validation.attributes.title'),
            'type'          => __('validation.attributes.type'),
            'status'        => __('validation.attributes.status'),
            'start_at'      => __('validation.attributes.start_at'),
            'deadline_at'   => __('validation.attributes.deadline_at'),
            'from'          => __('validation.attributes.from'),
            'to'            => __('validation.attributes.to'),
            'per_page'      => __('validation.attributes.per_page'),
        ];
    }
}
