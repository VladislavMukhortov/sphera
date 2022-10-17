<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class AchievementResource extends BaseResource
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
            'description' => $this->description,
            'date'        => $this->date->format('d-m-Y'),
            'auto'        => (bool)$this->auto,
            'goal'        => GoalResource::make($this->whenLoaded('goal')),
            'goal_url'    => $this->auto ? route('goals.show', $this->goal_id) : null,
            'skill'       => SkillResource::make($this->whenLoaded('skill')),
        ];
    }
}
