<?php

namespace App\Http\Resources\Profile;

use App\Http\Resources\{BaseResource, UserResource};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserFamilyResource extends BaseResource
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
            'is_child'      => (bool)$this->is_child,
            'full_name'     => $this->full_name,
            'photo'         => Storage::disk('public')->url($this->photo ?? 'default.jpg'),
            'position'      => $this->position,
            'since'         => $this->since->format('d-m-Y'),
            'user'          => UserResource::make($this->whenLoaded('user'))
        ];
    }
}
