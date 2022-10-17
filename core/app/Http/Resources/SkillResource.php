<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class SkillResource extends BaseResource
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
            'id'            => (int) $this->id,
            'title'         => $this->locale->title,
        ];
    }
}
