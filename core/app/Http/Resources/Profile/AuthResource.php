<?php

namespace App\Http\Resources\Profile;

use App\Http\Resources\{AchievementResource,
    ActivityResource,
    BaseResource,
    FollowResource,
    GoalResource,
    LocationResource,
    PostResource,
    ReportResource,
    StudentsCollection};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuthResource extends BaseResource
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
            'uuid'          => $this->uuid,
            'email'         => $this->email,
            'phone'         => $this->phone,
            'age'           => (int)$this->birthday?->age,
            'gender'        => $this->gender,
            'is_banned'     => (bool)$this->is_banned,
            'is_mentor'     => (bool)$this->is_mentor,
            'first_name'    => $this->first_name,
            'last_name'     => $this->last_name,
            'birthday'      => $this->birthday?->format('d-m-Y'),
            'lang'          => $this->lang,
            'country'       => LocationResource::make($this->country),
            'city'          => LocationResource::make($this->city),
            'photo'         => Storage::disk('public')->url($this->photo ?? 'default.jpg'),
            'joined_at'     => $this->created_at->format('d-m-Y'),
            'rating'        => (float)$this->rating,

            'settings'      => UserSettingsCollection::make($this->whenLoaded('settings')),
            'notifications' => UserNotificationSettingsCollection::make($this->whenLoaded('settings')),
            'signins'       => SignInResource::collection($this->whenLoaded('signins')),
            'activities'    => ActivityResource::collection($this->whenLoaded('activities')),
            'career'        => UserCareerResource::collection($this->whenLoaded('career')),
            'education'     => UserEducationResource::collection($this->whenLoaded('education')),
            'skills_hobby'  => UserSkillResource::collection($this->whenLoaded('hobbySkills')),
            'skills_mentor' => UserSkillResource::collection($this->whenLoaded('mentorSkills')),
            'posts'         => PostResource::collection($this->whenLoaded('posts')),
            'mentoredGoals' => GoalResource::collection($this->whenLoaded('mentoredGoals')),
            'goals'         => GoalResource::collection($this->whenLoaded('goals')),
            'students'      => $this->when($this->is_mentor, StudentsCollection::make($this->students)),
            'followers'     => FollowResource::collection($this->whenLoaded('followers')),
            'reports'       => ReportResource::collection($this->whenLoaded('reports')),
            'achievements'  => AchievementResource::collection(($this->whenLoaded('achievements'))),
        ];
    }
}
