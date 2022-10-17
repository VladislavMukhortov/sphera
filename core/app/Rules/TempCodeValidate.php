<?php

namespace App\Rules;

use App\Models\TempCode;
use Illuminate\Contracts\Validation\{DataAwareRule, Rule};

final class TempCodeValidate implements Rule, DataAwareRule
{
    /**
     * All the data under validation.
     *
     * @var array
     */
    protected array $data = [];

    /**
     * Set the data under validation.
     *
     * @param  array  $data
     * @return $this
     */
    public function setData($data): TempCodeValidate
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $login = $this->getFormatLogin();
        if (empty($login)) {
            return false;
        }

        return config('app.debug') === true || TempCode::checkCode($value, $login);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Code incorrect!';
    }

    /**
     * Форматируем логин
     *
     * @return mixed
     */
    private function getFormatLogin(): mixed
    {
        $login = $this->data['login'] ?? [];

        return !empty($login) && email_or_phone($login) === 'phone'
            ? phone_format_convert($login)
            : $login;
    }
}
