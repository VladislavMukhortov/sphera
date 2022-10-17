<?php

namespace App\Models\Profile;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSetting extends Model
{
    use HasFactory;

    protected $table = 'user_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'setting',
        'value',
    ];

    /**
     * Основные настройки аккаунта
     *
     * @var string[]
     */
    public const MAIN = [
        'first_name',
        'last_name',
        'gender',
        'birthday',
        'email',
        'phone',
        'is_mentor',
        'country_id',
        'city_id',
        'photo',
    ];

    public const OFFER_BECOME_MENTOR = 0;
    public const OFFER_BECOME_STUDENT = 1;
    public const BECOME_MENTOR_ACCEPTED = 2;
    public const BECOME_MENTOR_DECLINED = 3;
    public const BECOME_STUDENT_ACCEPTED = 4;
    public const BECOME_STUDENT_DECLINED = 5;
    public const NEW_MENTOR_GOAL = 6;
    public const NEW_STUDENT_GOAL = 7;
    public const MENTOR_PERSONAL_GOAL_FINISHED = 8;
    public const STUDENT_PERSONAL_GOAL_FINISHED = 9;
    public const MY_GOAL_FINISHED = 10;
    public const NEW_DIALOGS = 11;
    public const NEW_MESSAGES = 12;
    public const SEARCH_MENTOR = 13;
    public const NEW_FOLLOWER = 14;
    public const NEW_FOLLOW_GOAL = 15;
    public const FOLLOW_GOAL_UPDATED = 16;
    public const FOLLOW_GOAL_MENTOR_CHANGED = 17;
    public const FOLLOW_ADD_NEW_STUDENT = 18;
    public const FOLLOW_SEARCH_MENTOR = 19;
    public const FOLLOW_GOAL_FINISHED = 20;
    public const MY_GOAL_UPDATED = 21;
    public const NEW_GOAL_COMMENT = 22;
    public const NEW_REPORT_COMMENT = 23;

    /**
     * Опции уведомлений для аккаунта
     *
     * @var array
     */
    public const NOTIFICATIONS = [
        self::OFFER_BECOME_MENTOR => 'become_mentor_offer', //предложение стать ментором
        self::OFFER_BECOME_STUDENT => 'become_student_offer', //предложение стать учеником
        self::BECOME_MENTOR_ACCEPTED => 'become_mentor_accepted', //предложение стать ментором принято
        self::BECOME_MENTOR_DECLINED => 'become_mentor_declined', //отказ от предложения стать ментором
        self::BECOME_STUDENT_ACCEPTED => 'become_student_accepted', //предложение стать учеником принято
        self::BECOME_STUDENT_DECLINED => 'become_student_declined', //отказ от предложения стать учеником
        self::NEW_MENTOR_GOAL => 'new_mentor_goal', //новая цель ментора
        self::NEW_STUDENT_GOAL => 'new_student_goal', //новая цель ученика
        self::MENTOR_PERSONAL_GOAL_FINISHED => 'mentor_personal_goal_finished', //цель ментора завершена
        self::STUDENT_PERSONAL_GOAL_FINISHED => 'student_personal_goal_finished', //цель ученика завершена
        self::MY_GOAL_FINISHED => 'my_goal_finished', //собственная цель завершена
        self::NEW_DIALOGS => 'new_dialogs_in_private_messages', //новые диалоги
        self::NEW_MESSAGES => 'new_private_messages', //новое сообщение
        self::SEARCH_MENTOR => 'search_mentor', //поиск ментора
        self::NEW_FOLLOWER => 'new_follower', //новый подписчик
        self::NEW_FOLLOW_GOAL => 'new_follow_goal', //новая цель у подписки
        self::FOLLOW_GOAL_UPDATED => 'follow_goal_updated', //цель подписки обновлена
        self::FOLLOW_GOAL_MENTOR_CHANGED => 'follow_goal_mentor_changed', //цель подписки сменила ментора
        self::FOLLOW_ADD_NEW_STUDENT => 'follow_add_new_student', //у подписки новый ученик
        self::FOLLOW_SEARCH_MENTOR => 'follow_search_mentor', //подписка ищет ментора
        self::FOLLOW_GOAL_FINISHED => 'follow_goal_finished', //подписка завершила цель
        self::MY_GOAL_UPDATED => 'my_goal_updated', //обновлен прогресс по собственной цели
        self::NEW_GOAL_COMMENT => 'new_goal_comment', //новый комментарий по цели
        self::NEW_REPORT_COMMENT => 'new_report_comment', //новый комментарий по отчету
    ];

    /** Уведомления, связанные с целью */
    public const GOAL_NOTIFICATIONS = [
        self::NOTIFICATIONS[self::OFFER_BECOME_MENTOR],
        self::NOTIFICATIONS[self::OFFER_BECOME_STUDENT],
        self::NOTIFICATIONS[self::BECOME_MENTOR_ACCEPTED],
        self::NOTIFICATIONS[self::BECOME_MENTOR_DECLINED],
        self::NOTIFICATIONS[self::BECOME_STUDENT_ACCEPTED],
        self::NOTIFICATIONS[self::BECOME_STUDENT_DECLINED],
        self::NOTIFICATIONS[self::NEW_MENTOR_GOAL],
        self::NOTIFICATIONS[self::NEW_STUDENT_GOAL],
        self::NOTIFICATIONS[self::MENTOR_PERSONAL_GOAL_FINISHED],
        self::NOTIFICATIONS[self::STUDENT_PERSONAL_GOAL_FINISHED],
        self::NOTIFICATIONS[self::MY_GOAL_FINISHED],
        self::NOTIFICATIONS[self::NEW_FOLLOW_GOAL],
        self::NOTIFICATIONS[self::FOLLOW_GOAL_UPDATED],
        self::NOTIFICATIONS[self::FOLLOW_GOAL_MENTOR_CHANGED],
        self::NOTIFICATIONS[self::FOLLOW_SEARCH_MENTOR],
        self::NOTIFICATIONS[self::FOLLOW_GOAL_FINISHED],
        self::NOTIFICATIONS[self::MY_GOAL_UPDATED],
        self::NOTIFICATIONS[self::NEW_GOAL_COMMENT],
    ];

    /** Уведомления, НЕ связанные с целью */
    public const GOALLESS_NOTIFICATIONS = [
        self::NOTIFICATIONS[self::NEW_DIALOGS],
        self::NOTIFICATIONS[self::NEW_MESSAGES],
        self::NOTIFICATIONS[self::NEW_FOLLOWER],
        self::NOTIFICATIONS[self::FOLLOW_ADD_NEW_STUDENT],
    ];

    /**
     * Массив уведомлений о комментариях
     */
    public const COMMENTS = [
        self::NOTIFICATIONS[self::NEW_REPORT_COMMENT],
        self::NOTIFICATIONS[self::NEW_GOAL_COMMENT]
    ];

    /**
     * Опции настроек приватности
     *
     * @var array
     */
    public const PRIVACY_SETTINGS = [
            'main_info_visible',
            'statistics_visible',
            'search_visible',
            'goals_in_progress_visible',
            'achievements_visible',
            'goals_complete_visible',
            'goals_overdue_visible',
            'goals_paused_visible',
            'goals_details_open',
            'goals_favorites_add',
            'goals_copy',
            'goals_comments_visible',
            'goals_comments_write',
            'mentoring_offer',
            'mentoring_become',
            'reports_visible',
            'reports_comments',
    ];

    /**
     * Возвращает пользователя
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
