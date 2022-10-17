<?php

namespace App\Providers;

use App\Models\Profile\UserSetting;
use App\Models\User;
use App\Observers\UserObserver;
use App\Observers\UserSettingObserver;
use App\Events\{Auth\SignInEvent,
    Auth\SignUpEvent,
    FeedbackCreateEvent,
    FollowSearchMentorEvent,
    GoalFinishedEvent,
    GoalUpdatedEvent,
    ActivityEvent,
    MentorUpdatedEvent,
    NewGoalCommentEvent,
    NewGoalEvent,
    NewReportCommentEvent,
    OfferAutoCanceledEvent};
use App\Listeners\{ActivitiesListener,
    FeedbackListener,
    FollowersListener,
    MakeDevCoinsListener,
    MentorsListener,
    OfferAutoCanceledListener,
    LogInListener,
    SignUpListener,
    StudentsListener,
    UserSelfListener};
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SignUpEvent::class => [
            SignUpListener::class,
            MakeDevCoinsListener::class,
            LogInListener::class,
        ],
        SignInEvent::class => [
            LogInListener::class,
        ],
        OfferAutoCanceledEvent::class => [
            OfferAutoCanceledListener::class,
        ],
        //GOALS
        NewGoalEvent::class => [
            FollowersListener::class,
            MentorsListener::class,
            StudentsListener::class,
        ],
        GoalUpdatedEvent::class => [
            FollowersListener::class,
            UserSelfListener::class,
        ],
        GoalFinishedEvent::class => [
            UserSelfListener::class,
            FollowersListener::class,
            MentorsListener::class,
            StudentsListener::class,
        ],
        MentorUpdatedEvent::class => [
            FollowersListener::class,
        ],
        //POSTS
        FollowSearchMentorEvent::class => [
            FollowersListener::class,
        ],
        //COMMENTS
        NewGoalCommentEvent::class => [
            UserSelfListener::class,
        ],
        NewReportCommentEvent::class => [
            UserSelfListener::class,
        ],
        //ACTIVITIES
        ActivityEvent::class => [
            ActivitiesListener::class,
        ],
        FeedbackCreateEvent::class => [
            FeedbackListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        UserSetting::observe(UserSettingObserver::class);
        User::observe(UserObserver::class);
    }
}
