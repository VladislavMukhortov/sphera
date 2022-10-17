<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class OfferResource extends BaseResource
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
            'id' => (int)$this->id,
            'type' => $this->type,
            'sender' => UserResource::make($this->sender),
            'goal' => GoalResource::make($this->goal),
            'goal_link' => route('goals.show', $this->goal),
            'amount' => $this->amount,
        ];
    }
}
