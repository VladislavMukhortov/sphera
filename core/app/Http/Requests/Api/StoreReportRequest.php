<?php

namespace App\Http\Requests\Api;

use Illuminate\Http\Exceptions\HttpResponseException;

class StoreReportRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->reports()->whereDate('created_at', now())->doesntExist();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'description'   => ['required', 'string'],
            'photo'         => ['filled', 'image', 'mimes:jpg,jpeg,png,bmp', 'max:10000'],
            'goal_id'       => ['filled', 'exists:goals,id'],
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
            'description' => __('validation.attributes.description'),
            'photo'       => __('validation.attributes.image'),
            'goal_id'     => __('validation.attributes.goal_id'),
        ];
    }

    /**
     * Ошибка при попытке создать более одного дневного отчета в день
     *
     * @return void
     */
    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'error' => 'Only one report per day is allowed.',
                'errors' => (object)[],
            ], 403)
        );
    }
}
