<?php

namespace App\Http\Resources\Profile;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class UserCareerResource extends BaseResource
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
            'id'            => (int)$this->id,
            'company_name'  => $this->company_name,
            'position_name' => $this->position_name,
            'date_start'    => $this->date_start->format('d-m-Y'),
            'date_end'      => $this->date_end?->format('d-m-Y'),
        ];
    }
}
