<?php

namespace App\Http\Requests\Api\Profile;

use App\Http\Requests\Api\BaseRequest;
use App\Rules\PhoneOrEmail;
use Illuminate\Validation\Rule;

class UpdateLoginRequest extends BaseRequest
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
            'login'    => ['required', new PhoneOrEmail($this->user())],
            'code_old' => [
                'required',
                Rule::exists('temp_codes', 'code')->where(
                    fn($q) => $q->whereIn('login', [$this->user()->phone, $this->user()->email])
                )
            ],
            'code_new' => [
                'required',
                Rule::exists('temp_codes', 'code')->where(
                    fn($q) => $q->whereIn('login', [$this->login])
                )
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
            'login'     => __('validation.attributes.login'),
            'code_old'  => __('validation.attributes.code_old'),
            'code_new'  => __('validation.attributes.code_new'),
        ];
    }
}
