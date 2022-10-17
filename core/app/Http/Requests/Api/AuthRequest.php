<?php

namespace App\Http\Requests\Api;

use App\Rules\{PhoneOrEmail, TempCodeValidate};

class AuthRequest extends BaseRequest
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
            'login' => ['required', 'string', new PhoneOrEmail()],
            'code'  => ['required', new TempCodeValidate()],
        ];
    }
}
