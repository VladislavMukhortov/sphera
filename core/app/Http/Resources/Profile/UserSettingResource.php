<?php

namespace App\Http\Resources\Profile;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class UserSettingResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'name'  => $this->setting,
            'value' => $this->value
        ];
    }
}
