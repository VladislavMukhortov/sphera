<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class PostResource extends BaseResource
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
            'id'          => (int)$this->id,
            'title'       => $this->title,
            'amount'      => (int)$this->amount,
            'type'        => $this->type,
            'user'        => UserResource::make($this->whenLoaded('user')),
            'goal'        => GoalResource::make($this->whenLoaded('goal')),
            'tags'        => TagResource::collection($this->whenLoaded('tags')),
        ];
    }
}
