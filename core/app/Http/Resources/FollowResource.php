<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FollowResource extends BaseResource
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
            'uuid'          => $this->uuid,
            'first_name'    => $this->first_name,
            'last_name'     => $this->last_name,
            'photo'         => Storage::disk('public')->url($this->photo ?? 'default.jpg'),
            'position'      => $this->current_position,
            'rating'        => (float)$this->rating,
            'url'           => route('user.getProfile', $this->uuid),
        ];
    }
}
