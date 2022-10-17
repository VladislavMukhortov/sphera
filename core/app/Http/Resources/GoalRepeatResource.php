<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class GoalRepeatResource extends BaseResource
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
            'count'             => (int)$this->count,
            'step_percent'      => (int)$this->percent,
            'created_at'        => $this->created_at->format('d-m-Y'),
            'goal'              => GoalResource::make($this->whenLoaded('goal')),
        ];
    }
}
