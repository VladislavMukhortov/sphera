<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportResource extends BaseResource
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
            'id'                => (int)$this->id,
            'description'       => $this->description,
            'file'              => $this->file ? Storage::disk('public')->url($this->file) : null,
            'created_at'        => $this->created_at?->format('d-m-Y'),
            'comments'          => CommentResource::collection($this->whenLoaded('comments')),
            'comments_count'    => $this->comments_count,
            'reactions'         => [],//заглушка под реакции
            'goal'              => GoalResource::make($this->whenLoaded('goal')),
            'user'              => UserResource::make($this->whenLoaded('user')),
        ];
    }
}
