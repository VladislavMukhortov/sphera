<?php

namespace App\Http\Requests\Api;

class GetAchievementsRequest extends BaseRequest
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
            'per_page' => ['filled', 'int'],
            'auto'     => ['filled', 'in:true,false,1,0']
        ];
    }
}
