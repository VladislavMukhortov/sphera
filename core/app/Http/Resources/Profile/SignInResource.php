<?php

namespace App\Http\Resources\Profile;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class SignInResource extends BaseResource
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
            'ip'            => $this->ip,
            'is_mobile'     => (bool)$this->is_mobile,
            'location'      => $this->location,
            'region'        => $this->region,
            'device'        => $this->device,
            'user_agent'    => $this->user_agent,
            'os'            => $this->os,
            'os_ver'        => $this->os_ver,
            'browser'       => $this->browser,
            'browser_ver'   => $this->browser_ver,
            'data'          => $this->created_at->format('d-m-Y'),
            'last_active'   => $this->lastActive,
        ];
    }
}
