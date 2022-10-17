<?php

namespace App\Http\Requests\Api;

use App\Models\UserNotification;
use Illuminate\Validation\Rule;

class UpdateUserNotificationRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user_notification->user_id === $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(UserNotification::STATUSES)],
        ];
    }
}
