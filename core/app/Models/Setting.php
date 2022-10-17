<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'parameter',
        'value',
    ];

    public const DEFAULT_RATING = 'default_rating';
    public const FOLLOW = 'follow';
    public const UNFOLLOW = 'unfollow';
    public const FORCE_UNFOLLOW = 'force_unfollow';
    public const NEW_GOAL = 'new_goal';
    public const NEW_META_GOAL = 'new_meta_goal'; //todo пока не отслеживается
    public const GOAL_PROGRESS = 'goal_progress';
    public const TOP_UP_BALANCE = 'top_up_balance'; //todo пока не отслеживается
    public const NEW_ACHIEVEMENT = 'new_achievement';
    public const NEW_DAILY_REPORT = 'new_daily_report';
    public const NEW_GOAL_REPORT = 'new_goal_report'; //todo пока не отслеживается
    public const NEW_COMMENT_FOR_MENTOR = 'new_comment_for_mentor'; //todo пока не отслеживается
    public const NEW_STUDENT = 'new_student'; //todo пока не отслеживается
    public const NEW_REQUEST_FOR_MENTOR = 'new_request_for_mentor';
    public const NEW_FAVORITE_GOAL = 'new_favorite_goal'; //todo пока не отслеживается
    public const NEW_DRAFT_GOAL = 'new_draft_goal'; //todo пока не отслеживается
    public const NEW_COMMENT = 'new_comment';
    public const NEW_COMMENT_ANSWER = 'new_comment_answer';
    public const FOLLOW_AMOUNT = 'follow_amount';

    public const TRANSLATIONS = [
        self::DEFAULT_RATING => '(Рейтинг) Кол-во после регистрации',
        self::FOLLOW => '(Рейтинг) Подписка на пользователя',
        self::UNFOLLOW => '(Рейтинг) Отписка от пользователя',
        self::FORCE_UNFOLLOW => '(Рейтинг) Удаление подписчика',
        self::NEW_GOAL => '(Рейтинг) Создание цели',
        self::NEW_META_GOAL => '(Рейтинг) Создание мета-цели',
        self::TOP_UP_BALANCE => '(Рейтинг) Пополнение кошелька',
        self::GOAL_PROGRESS => '(Рейтинг) Действие по цели(круг/пункт)',
        self::NEW_ACHIEVEMENT => '(Рейтинг) Добавление пользовательского достижения',
        self::NEW_DAILY_REPORT => '(Рейтинг) Создание отчета по дню',
        self::NEW_GOAL_REPORT => '(Рейтинг) Создание отчета по цели',
        self::NEW_COMMENT_FOR_MENTOR => '(Рейтинг) Оставление отзыва о менторе',
        self::NEW_STUDENT => '(Рейтинг) Взятие ученика на менторство',
        self::NEW_REQUEST_FOR_MENTOR => '(Рейтинг) Оставление запроса на менторство',
        self::NEW_FAVORITE_GOAL => '(Рейтинг) Добавление цели в избранное',
        self::NEW_DRAFT_GOAL => '(Рейтинг) Добавление цели в черновик',
        self::NEW_COMMENT => '(Рейтинг) Комментирование (любое)',
        self::NEW_COMMENT_ANSWER => '(Рейтинг) Ответы на комментарии',
        self::FOLLOW_AMOUNT => '(Оплата) Стоимость подписки',
    ];
}
