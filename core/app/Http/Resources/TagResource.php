<?php

namespace App\Http\Resources;

class TagResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'        => (int)$this->id,
            'title'     => $this->title,
        ];
    }
}
