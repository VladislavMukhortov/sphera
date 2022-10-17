<?php

namespace App\Http\Requests\Api;

class GetTaskRequest extends BaseRequest
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
            'is_completed' => ['filled', 'boolean'],
            'start_at'     => ['filled', 'date'],
            'deadline_at'  => ['filled', 'date'],
            'from'         => ['filled', 'date'],
            'to'           => ['filled', 'date']
        ];
    }
}
