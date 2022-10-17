<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class ActivityResource extends BaseResource
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
            'amount'     => (float)$this->amount,
            'created_at' => $this->created_at->format('d-m-Y'),
        ];
    }
}
