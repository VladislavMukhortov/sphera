<?php

namespace App\Http\Requests\Api\Profile;

use App\Http\Requests\Api\BaseRequest;

class UpdateUserSettingsRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return !empty($this->request->all()) || $this->hasFile('photo');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name'                => ['filled', 'string', 'max:60'],
            'last_name'                 => ['filled', 'string', 'max:60'],
            'gender'                    => ['filled', 'string', 'in:male,female'],
            'birthday'                  => ['filled', 'date'],
            'post_visibility'           => ['filled', 'in:all,private,mentors,followers'],
            'task_visibility'           => ['filled', 'in:all,private,mentors,followers'],
            'goal_visibility'           => ['filled', 'in:all,private,mentors,followers'],
            'goal_comment'              => ['filled', 'in:all,private,mentors,followers'],
            'schedule_visibility'       => ['filled', 'in:all,private,mentors,followers'],
            'profile_visibility'        => ['filled', 'in:all,private,mentors,followers'],
            'subscribe'                 => ['filled', 'in:all,private,mentors,followers'],
            'achievement_visibility'    => ['filled', 'in:all,private,mentors,followers'],
            'report_visibility'         => ['filled', 'in:all,private,mentors,followers'],
            'main_info_visible'         => ['filled', 'in:all,private,mentors,followers'],
            'statistics_visible'        => ['filled', 'in:all,private,mentors,followers'],
            'search_visible'            => ['filled', 'in:all,private,mentors,followers'],
            'goals_in_progress_visible' => ['filled', 'in:all,private,mentors,followers'],
            'achievements_visible'      => ['filled', 'in:all,private,mentors,followers'],
            'goals_complete_visible'    => ['filled', 'in:all,private,mentors,followers'],
            'goals_overdue_visible'     => ['filled', 'in:all,private,mentors,followers'],
            'goals_paused_visible'      => ['filled', 'in:all,private,mentors,followers'],
            'goals_details_open'        => ['filled', 'in:all,private,mentors,followers'],
            'goals_favorites_add'       => ['filled', 'in:all,private,mentors,followers'],
            'goals_copy'                => ['filled', 'in:all,private,mentors,followers'],
            'goals_comments_visible'    => ['filled', 'in:all,private,mentors,followers'],
            'goals_comments_write'      => ['filled', 'in:all,private,mentors,followers'],
            'mentoring_offer'           => ['filled', 'in:all,private,mentors,followers'],
            'mentoring_become'          => ['filled', 'in:all,private,mentors,followers'],
            'reports_visible'           => ['filled', 'in:all,private,mentors,followers'],
            'reports_comments'          => ['filled', 'in:all,private,mentors,followers'],
            'family_visibility'         => ['filled', 'in:all,private'],
            'career_visibility'         => ['filled', 'in:all,private'],
            'education_visibility'      => ['filled', 'in:all,private'],
            'offer_mentoring'           => ['filled', 'in:all,private'],
            'ask_mentoring'             => ['filled', 'in:all,private'],
            'notifications'             => ['nullable', 'string', 'exclude'],
            'country_id'                => ['filled', 'int', 'exists:countries,id'],
            'city_id'                   => ['filled', 'int', 'exists:cities,id'],
            'is_mentor'                 => ['filled', 'in:1,0'],
            'photo'                     => ['filled', 'image', 'mimes:jpg,jpeg,png', 'max:10000'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'first_name'             => __('validation.attributes.first_name'),
            'last_name'              => __('validation.attributes.last_name'),
            'gender'                 => __('validation.attributes.gender'),
            'birthday'               => __('validation.attributes.birthday'),
            'notifications'          => __('validation.attributes.notifications'),
            'family_visibility'      => __('validation.attributes.family_visibility'),
            'career_visibility'      => __('validation.attributes.career_visibility'),
            'education_visibility'   => __('validation.attributes.education_visibility'),
            'post_visibility'        => __('validation.attributes.post_visibility'),
            'task_visibility'        => __('validation.attributes.task_visibility'),
            'goal_visibility'        => __('validation.attributes.goal_visibility'),
            'goal_comment'           => __('validation.attributes.goal_comment'),
            'achievement_visibility' => __('validation.attributes.achievement_visibility'),
            'report_visibility'      => __('validation.attributes.report_visibility'),
            'schedule_visibility'    => __('validation.attributes.schedule_visibility'),
            'profile_visibility'     => __('validation.attributes.profile_visibility'),
            'offer_mentoring'        => __('validation.attributes.offer_mentoring'),
            'ask_mentoring'          => __('validation.attributes.ask_mentoring'),
            'subscribe'              => __('validation.attributes.subscribe'),
            'country_id'             => __('validation.attributes.country_id'),
            'is_mentor'              => __('validation.attributes.is_mentor'),
            'city_id'                => __('validation.attributes.city_id'),
            'photo'                  => __('validation.attributes.photo'),
        ];
    }
}
