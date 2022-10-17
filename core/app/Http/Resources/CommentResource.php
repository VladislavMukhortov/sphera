<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class CommentResource extends BaseResource
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
            'id'         => (int)$this->id,
            'body'       => $this->body,
            'created_at' => $this->created_at->format('d-m-Y'),
            'updated_at' => $this->updated_at->format('d-m-Y'),
            'user'       => UserResource::make($this->whenLoaded('user')),
            'user_url'   => $this->when(
                $this->whenLoaded('user', true, false),
                route('user.getProfile', $this->user),
                null
            ),
            'replies' => CommentResource::collection($this->whenLoaded('replies')),
        ];
    }
}
