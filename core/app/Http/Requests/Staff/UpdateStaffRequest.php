<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateStaffRequest extends FormRequest
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
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['bail', 'nullable', 'max:64', Rule::unique('staff', 'email')->ignore($this->staff->id, 'id')],
            'password'      => ['bail', 'nullable', 'string', Password::min(8)->letters()->symbols()->uncompromised()],
            'access_level'  => ['required', 'integer']
        ];
    }
}
