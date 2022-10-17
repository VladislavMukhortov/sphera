<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule as ValidationRule;

final class PhoneOrEmail implements Rule
{
    /**
     * User instance
     *
     * @var User|null
     */
    private ?User $user;

    /**
     * Create a new instance.
     *
     * @param User|null $user
     */
    public function __construct(?User $user = null)
    {
        $this->user = $user;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if (is_array($value)) {
            return false;
        }

        $type = email_or_phone($value);
        $rules = [
            'login' => ['required', 'email:rfc,dns', 'max:64'],
        ];

        if ($type === 'email' && config('app.debug', false) === true) {
            $rules = [
                'login' => [
                    'required',
                    'max:64',
                    $this->user
                        ? ValidationRule::unique('users', 'email')->ignore($this->user->id)
                        : ''
                ]
            ];
        } elseif ($type === 'phone') {
            $value = phone_format_convert($value);
            $rules = [
                'login' => [
                    'required',
                    'digits_between:8,12',
                    $this->user
                        ? ValidationRule::unique('users', 'phone')->ignore($this->user->id)
                        : ''
                ]
            ];
        }

        return !Validator::make(['login' => $value], $rules)->fails();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return trans('validation.attributes.login');
    }
}
