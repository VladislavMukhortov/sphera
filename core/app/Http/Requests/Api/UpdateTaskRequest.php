<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

class UpdateTaskRequest extends BaseRequest
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
            'title'        => ['filled', 'string'],
            'comment'      => ['filled', 'string'],
            'price'        => ['filled', 'int'],
            'schedule'     => ['filled', 'string'],
            'is_completed' => ['filled', 'boolean', Rule::when($this->goal->deadline_at < now(), 'in:0,false')],
            'start_at'     => ['filled', 'date'],
            'deadline_at'  => ['filled', 'date']
        ];
    }
}
