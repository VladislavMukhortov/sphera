<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class TaskResource extends BaseResource
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
            'id'           => (int)$this->id,
            'title'        => $this->title,
            'comment'      => $this->comment,
            'price'        => (int)$this->price,
            'schedule'     => $this->schedule,
            'is_completed' => (bool)$this->is_completed,
            'start_at'     => $this->start_at?->format('d-m-Y'),
            'deadline_at'  => $this->deadline_at?->format('d-m-Y'),
            'goal'         => GoalResource::make($this->whenLoaded('goal')),
        ];
    }
}
