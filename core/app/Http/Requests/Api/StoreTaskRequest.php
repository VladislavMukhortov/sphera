<?php

namespace App\Http\Requests\Api;

class StoreTaskRequest extends BaseRequest
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
            'title'        => ['required', 'string'],
            'comment'      => ['filled', 'string'],
            'price'        => ['required', 'int'],
            'schedule'     => ['required', 'string'],
            'is_completed' => ['required', 'boolean'],
            'start_at'     => ['filled', 'date'],
            'deadline_at'  => ['filled', 'date']
        ];
    }
}
