<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Profile\{
    UserCareerResource,
    UserEducationResource,
    UserSkillResource
};
use Illuminate\Http\Request;

class UserResource extends BaseResource
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
            'uuid'              => $this->uuid,
            'email'             => $this->email,
            'phone'             => $this->phone,
            'age'               => (int)$this->birthday?->age,
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            'country'           => LocationResource::make($this->country),
            'city'              => LocationResource::make($this->city),
            'photo'             => Storage::disk('public')->url($this->photo ?? 'default.jpg'),
            'joined_at'         => $this->created_at->format('d-m-Y'),
            'position'          => $this->current_position,
            'is_mentor'         => (bool)$this->is_mentor,
            'rating'            => (float)$this->rating,

            'already_follow'    => $this->whenAppended('isAlreadyFollow', (bool)$this->already_follow),
            'already_mentor'    => $this->whenAppended('isAlreadyMentor', (bool)$this->already_mentor),
            'activities'        => ActivityResource::collection($this->whenLoaded('activities')),
            'career'            => UserCareerResource::collection($this->whenLoaded('career')),
            'education'         => UserEducationResource::collection($this->whenLoaded('education')),
            'skills_hobby'      => UserSkillResource::collection($this->whenLoaded('hobbySkills')),
            'skills_mentor'     => UserSkillResource::collection($this->whenLoaded('mentorSkills')),
            'posts'             => PostResource::collection($this->whenLoaded('posts')),
            'mentoredGoals'     => GoalResource::collection($this->whenLoaded('mentoredGoals')),
            'goals'             => GoalResource::collection($this->whenLoaded('goals')),
            'students'          => $this->when($this->is_mentor, StudentsCollection::make($this->students)),
            'followers'         => FollowResource::collection($this->whenLoaded('followers')),
            'reports'           => ReportResource::collection($this->whenLoaded('reports')),
            'achievements'      => AchievementResource::collection(($this->whenLoaded('achievements'))),
        ];
    }
}
