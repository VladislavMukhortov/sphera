<?php

namespace App\Http\Resources\Profile;

use App\Http\Resources\{BaseResource, SkillResource};
use Illuminate\Http\Request;

class UserSkillResource extends BaseResource
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
            'title'             => $this->when($this->title, $this->title),
            'base_skill'        => SkillResource::make($this->whenLoaded('baseSkill')),
            'nested_skills'     => UserSkillResource::collection($this->whenLoaded('nestedUserSkills')),
        ];
    }
}
