<?php

namespace App\Http\Resources;

class GoalOptionResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                => (int)$this->id,
            'action_button'     => $this->action_button,
            'unit'              => $this->unit,
            'target_count'      => (int)$this->target_count,
        ];
    }
}
