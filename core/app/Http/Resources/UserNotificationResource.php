<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class UserNotificationResource extends BaseResource
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
            'type'              => $this->type,
            'amount'            => (int)$this->amount,
            'status'            => $this->status,
            'created_at'        => $this->created_at->format('d-m-Y'),
            'initiator'         => $this->when($this->user_id != $this->initiator_id, UserResource::make($this->sender)),
            'initiator_link'    => $this->when($this->user_id != $this->initiator_id, route('user.getProfile', $this->sender)),
            'target'            => $this->target,
            'target_link'       => $this->when($this->notifiable, $this->targetLink),
        ];
    }
}
