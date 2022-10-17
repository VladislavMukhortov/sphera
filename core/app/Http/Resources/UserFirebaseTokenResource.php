<?php

namespace App\Http\Resources;

class UserFirebaseTokenResource extends BaseResource
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
            'token' => $this->token
        ];
    }
}
