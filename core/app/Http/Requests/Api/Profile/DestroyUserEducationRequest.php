<?php

namespace App\Http\Requests\Api\Profile;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\BaseRequest;

class DestroyUserEducationRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->education->user_id == Auth::guard('sanctum')->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
