<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class GoalResource extends BaseResource
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
            'title'             => $this->title,
            'type'              => $this->type,
            'status'            => $this->status,
            'progress'          => $this->progress,
            'start_at'          => $this->start_at->format('d-m-Y'),
            'deadline_at'       => $this->deadline_at->format('d-m-Y'),
            'paused_at'         => $this->paused_at?->format('d-m-Y'),
            'owner'             => UserResource::make($this->whenLoaded('user')),
            'mentor'            => UserResource::make($this->whenLoaded('mentor')),
            'skill'             => SkillResource::make($this->whenLoaded('skill')),
            'tags'              => TagResource::collection($this->whenLoaded('tags')),
            'tasks'             => TaskResource::collection($this->whenLoaded('tasks')),
            'option'            => GoalOptionResource::make($this->whenLoaded('option')),
            'repeats'           => GoalRepeatResource::collection($this->whenLoaded('repeats')),
            'repeats_progress'  => $this->whenLoaded('repeats', $this->repeatProgress),
            'comments'          => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
