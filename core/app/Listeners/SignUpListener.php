<?php

namespace App\Listeners;

use App\Events\Auth\SignUpEvent;
use App\Models\{Goal, GoalOption, GoalRepeat, Profile\UserSetting, Report, Setting, Task, User};
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SignUpListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Обработка события регистрации нового пользователя
     *
     * @param SignUpEvent $event
     *
     * @return void
     */
    public function handle(SignUpEvent $event): void
    {
        $this->createDefaultSettings($event->user);

        $this->setDefaultRating($event->user);

        //todo данные для тестовых юзеров, удалить в продакшене
        if (config('app.debug')) {
            $this->createGoals($event->user);
            $this->createReports($event->user);
            $this->createNotifications($event->user);
            $this->createFollowers($event->user);
        }
    }

    /**
     * Создание дефолтных настроек
     *
     * @param User $user
     *
     * @return void
     */
    private function createDefaultSettings(User $user): void
    {
        DB::beginTransaction();
        try {
            $settings = [];
            foreach (UserSetting::PRIVACY_SETTINGS as $setting) {
                $settings[] = [
                    'setting' => $setting,
                    'value' => 'all'
                ];
            }
            $settings[] = [
                'setting' => 'notifications',
                'value' => '/' . implode('/', array_keys(UserSetting::NOTIFICATIONS)) . '/'
            ];
            $user->settings()->createMany($settings);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    /**
     * Задаем дефолтный рейтинг пользователя
     *
     * @param User $user
     *
     * @return void
     */
    private function setDefaultRating(User $user)
    {
        $user->update([
            'rating' => Setting::firstWhere('parameter', Setting::DEFAULT_RATING)->value('value')
        ]);
    }

    /**
     * Создание нескольких целей тестовому пользователю
     *
     * @param User $user
     *
     * @return void
     */
    private function createGoals(User $user): void
    {
        Goal::factory()
            ->count(3)
            ->afterCreating(function (Goal $goal) {
                if ($goal->type == 'list') {
                    Task::factory()->count(5)->create(['goal_id' => $goal->id]);
                } else {
                    GoalOption::factory()->afterCreating(function (GoalOption $goalOption) use ($goal) {
                        GoalRepeat::factory()->count(random_int(0, $goalOption->target_count - 1))
                            ->create(['goal_id' => $goal->id]);
                    })->create(['goal_id' => $goal->id]);
                }
            })
            ->create(['user_id' => $user->id]);
    }

    /**
     * Создаем тестовые отчеты
     *
     * @param User $user
     *
     * @return void
     */
    private function createReports(User $user): void
    {
        $user->goals()->each(function (Goal $goal) use ($user) {
            $user->reports()->createMany([
               [ 'description' => 'Test report ' . Str::random()],
               [ 'description' => 'Test report ' . Str::random(), 'goal_id' => $goal->id],
            ]);
        });
    }

    /**
     * Создаем тестовые уведомления
     *
     * @param User $user
     *
     * @return void
     */
    public function createNotifications(User $user): void
    {
        $notifications = [];
        foreach (UserSetting::GOAL_NOTIFICATIONS as $notification) {
            $notifications[] = [
                'notifiable_type' => Goal::class,
                'notifiable_id' => $user->goals()->inRandomOrder()->first()->id,
                'initiator_id' => in_array($notification, [UserSetting::MY_GOAL_FINISHED, UserSetting::SEARCH_MENTOR, UserSetting::MY_GOAL_UPDATED])
                    ? $user->id
                    : User::all()->whereNotIn('id', [$user->id])->random()->id,
                'type' => $notification,
                'status' => 'new'
            ];
        }
        foreach (UserSetting::GOALLESS_NOTIFICATIONS as $notification) {
            $notifications[] = [
                'initiator_id' => User::all()->whereNotIn('id', [$user->id])->random()->id,
                'type' => $notification,
                'status' => 'new'
            ];
        }
        $notifications[] = [
            'notifiable_type' => Report::class,
            'notifiable_id' => $user->reports()->inRandomOrder()->first()->id,
            'initiator_id' => User::all()->whereNotIn('id', [$user->id])->random()->id,
            'type' => UserSetting::NEW_REPORT_COMMENT,
            'status' => 'new'
        ];

        $user->notifications()->createMany($notifications);
    }

    /**
     * Создаем тестовых подписчиков
     *
     * @param User $user
     *
     * @return void
     */
    private function createFollowers(User $user): void
    {
        $testFollowerIds = User::whereNotIn('id', [$user->id])->pluck('id')->toArray();
        $user->followers()->attach($testFollowerIds);
    }
}
