<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class FeedbackResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'        => (int)$this->id,
            'goal_id'   => (int)$this->goal_id,
            'rank'      => (int)$this->rank,
            'comment'   => (string)$this->comment,
            'goal'      => GoalResource::make($this->whenLoaded('goal')),
        ];
    }
}
