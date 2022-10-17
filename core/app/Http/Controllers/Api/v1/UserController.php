<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\{ActivityEvent, MentorUpdatedEvent};
use App\Exceptions\NotEnoughCoinsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\{FollowRequest,
    ForceUnfollowRequest,
    MentoringDecisionRequest,
    PaginationRequest,
    Profile\SearchUserRequest,
    RemoveMentoringRequest,
    SendMentoringOfferRequest,
    ShowUserProfileRequest};
use App\Http\Resources\{FollowsCollection, OffersCollection, Profile\AuthResource, UserResource, UsersCollection};
use App\Models\{Goal, Offer, Profile\UserSetting, Setting, Transaction, User, UserNotification};
use App\Notifications\{MentoringDecisionNotification, MentoringRequestNotification, NewFollowerNotification};
use Illuminate\Http\{Request, Response};
use Illuminate\Support\Facades\DB;
use Throwable;

class UserController extends Controller
{
    /**
     * Поиск пользователя в базе.
     *
     * @param SearchUserRequest $request
     *
     * @return UserResource
     */
    public function search(SearchUserRequest $request): UserResource
    {
        $type = email_or_phone($request->login);
        $login = $type == 'phone'
            ? phone_format_convert($request->login)
            : $request->login;

        return new UserResource(
            User::query()->with(['settings', 'goals'])
                ->where($type, 'like', "%$login%")
                ->searchVisible()
                ->firstOrFail()
        );
    }

    /**
     * Возвращает менторов текущего пользователя с привязкой по целям
     *
     * @param Request $request
     *
     * @return UsersCollection
     */
    public function mentorList(Request $request): UsersCollection
    {
        return new UsersCollection(
            $request->user()->mentors->unique()->load([
                'mentoredGoals' => fn($q) => $q->where('user_id', $request->user()->id)->without(['tasks', 'mentor'])
            ])
        );
    }

    /**
     * Добавляет ментора к цели и отправляет уведомление
     *
     * @param MentoringDecisionRequest $request
     * @param Offer $offer
     *
     * @return Response
     * @throws Throwable
     */
    public function addMentor(MentoringDecisionRequest $request, Offer $offer): Response
    {
        throw_if(
            $offer->value('amount') > Transaction::balance($request->user()->id)->value('amount'),
            NotEnoughCoinsException::class
        );

        $mentor = $offer->sender;
        $goal = $offer->goal;

        if ($goal->update(['mentor_id' => $mentor->id])) {
            $offer->update(['status' => Offer::STATUSES[Offer::ACCEPTED]]);
            $offer->delete();

            (new UserNotification())->storeMentoringNotification($request->user()->id, $mentor->id, $goal, UserSetting::NOTIFICATIONS[UserSetting::BECOME_STUDENT_ACCEPTED]);

            $request->user()->notifications()
                ->where('notifiable_id', $goal->id)
                ->whereType(UserSetting::NOTIFICATIONS[UserSetting::SEARCH_MENTOR])->delete();

            if ($mentor->notificationAllowed(UserSetting::BECOME_STUDENT_ACCEPTED)) {
                $mentor->notify(new MentoringDecisionNotification($goal->title, $request->user(), Offer::STATUSES[Offer::ACCEPTED]));
            }
            event(new MentorUpdatedEvent($goal->withoutRelations()));
        }

        return response([
            'status' => true,
            'message' => 'Mentor successfully added.'
        ]);
    }

    /**
     * Добавляет ученика в рамках одной цели и отправляет уведомление
     *
     * @param MentoringDecisionRequest $request
     * @param Offer $offer
     *
     * @return Response
     */
    public function addStudent(MentoringDecisionRequest $request, Offer $offer): Response
    {
        $student = $offer->sender;
        $goal = $offer->goal;

        if ($goal->update(['mentor_id' => $request->user()->id])) {
            $offer->update(['status' => Offer::STATUSES[Offer::ACCEPTED]]);
            $offer->delete();

            (new UserNotification())->storeMentoringNotification($request->user()->id, $student->id, $goal, UserSetting::NOTIFICATIONS[UserSetting::BECOME_MENTOR_ACCEPTED]);
            $student->notifications()->where('notifiable_id', $goal->id)->whereType(UserSetting::NOTIFICATIONS[UserSetting::SEARCH_MENTOR])->delete();

            if ($student->notificationAllowed(UserSetting::BECOME_MENTOR_ACCEPTED)) {
                $student->notify(new MentoringDecisionNotification($goal->title, $request->user(), Offer::STATUSES[Offer::ACCEPTED]));
            }
            event(new MentorUpdatedEvent($goal->withoutRelations()));
        }

        return response([
            'status' => true,
            'message' => 'Student successfully added.'
        ]);
    }

    /**
     * Убирает ментора из цели
     *
     * @param RemoveMentoringRequest $request
     * @param Goal $goal
     *
     * @return Response
     */
    public function removeMentor(RemoveMentoringRequest $request, Goal $goal): Response
    {
        $status = $goal->update(['mentor_id' => null]);
        event(new MentorUpdatedEvent($goal->withoutRelations()));

        return response([
            'status' => $status,
            'message' => 'Mentor removed',
        ]);
    }

    /**
     * Возвращает учеников с привязкой по целям
     *
     * @param Request $request
     *
     * @return UsersCollection
     */
    public function studentList(Request $request): UsersCollection
    {
        return new UsersCollection(
            $request->user()->students->unique()->load([
                'goals' => fn($q) => $q->where('mentor_id', $request->user()->id)->without(['tasks', 'mentor'])
            ])
        );
    }

    /**
     * Отказаться от конкретной цели ученика
     *
     * @param RemoveMentoringRequest $request
     * @param Goal $goal
     *
     * @return Response
     */
    public function removeStudent(RemoveMentoringRequest $request, Goal $goal): Response
    {
        if ($status = $goal->update(['mentor_id' => null])) {
            event(new MentorUpdatedEvent($goal->withoutRelations()));
        } else $status = false;

        return response([
            'status' => $status,
            'message' => 'Student removed',
        ]);
    }

    /**
     * Запрос менторства (mentor/student) и отправка уведомления
     *
     * @param SendMentoringOfferRequest $request
     * @param Goal $goal
     * @param UserNotification $notification
     *
     * @return Response
     */
    public function sendOffer(SendMentoringOfferRequest $request, Goal $goal, UserNotification $notification): Response
    {
        $user = User::firstWhere('uuid', $request->user_uuid);
        $notificationId = $request->make_user == 'mentor' ? UserSetting::OFFER_BECOME_MENTOR : UserSetting::OFFER_BECOME_STUDENT;
        $type = UserSetting::NOTIFICATIONS[$notificationId];

        $user->offers()->firstOrCreate([
            'sender_id' => $request->user()->id,
            'goal_id' => $goal->id,
            'type' => $type,
        ], ['amount' => $request->amount ?? null]);
        $notification->storeMentoringNotification($request->user()->id, $user->id, $goal, $type, $request->amount ?? null);
        if ($user->notificationAllowed($notificationId)) {
            $user->notify(new MentoringRequestNotification($goal->title, $request->user(), $request->make_user));
        }

        return response([
            'status' => true,
            'message' => 'Notification sent',
        ]);
    }

    /**
     * Отказ от предложения по менторству и отправка уведомления
     *
     * @param MentoringDecisionRequest $request
     * @param Offer $offer
     *
     * @return Response
     */
    public function declineOffer(MentoringDecisionRequest $request, Offer $offer): Response
    {
        if ($offer->update(['status' => 'declined'])) {
            $initiator = $offer->sender;
            $goal = $offer->goal;
            $notificationId = $goal->user->id == $initiator->id ? UserSetting::BECOME_MENTOR_DECLINED : UserSetting::BECOME_STUDENT_DECLINED;
            $type = UserSetting::NOTIFICATIONS[$notificationId];
            $offer->delete();

            (new UserNotification())->storeMentoringNotification($request->user()->id, $initiator->id, $goal, $type);
            if ($initiator->notificationAllowed($notificationId)) {
                $initiator->notify(new MentoringDecisionNotification($goal->title, $request->user(), Offer::STATUSES[Offer::DECLINED]));
            }
        }

        return response([
            'status' => true,
            'message' => 'Mentoring offer declined',
        ]);
    }

    /**
     * Вывести все запросы по менторству
     *
     * @param Request $request
     *
     * @return OffersCollection
     */
    public function offers(Request $request): OffersCollection
    {
        return new OffersCollection($request->user()->offers);
    }

    /**
     * Подписаться на пользователя
     *
     * @param FollowRequest $request
     * @param User $user
     *
     * @return Response
     * @throws Throwable
     */
    public function follow(FollowRequest $request, User $user): Response
    {
        $amount = (int)Setting::where('parameter', Setting::FOLLOW_AMOUNT)->value('value');
        throw_if($amount > $request->user()->currentBalance(), new NotEnoughCoinsException());

        DB::beginTransaction();
        try {
            $request->user()->transactions()->create([
                'type' => config('balance.credit.follow'),
                'amount' => $amount,
                'target_id' => $user->id
            ]);
            $user->transactions()->create([
                'type' => config('balance.debit.follow'),
                'amount' => $amount,
                'who_id' => $request->user()->id
            ]);
            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();

            return response([
                'status' => false,
                'error' => 'Transaction failed',
                'errors' => (object)[
                    'transaction' => $exception->getMessage()
                ]
            ]);
        }

        $request->user()->follows()->attach($user->id, ['amount' => $amount]);
        $user->notifications()->create([
            'initiator_id' => $request->user()->id,
            'type' => UserSetting::NOTIFICATIONS[UserSetting::NEW_FOLLOWER],
        ]);
        $user->notify(new NewFollowerNotification($request->user()));
        event(new ActivityEvent($request->user(), Setting::FOLLOW));

        return response([
            'status' => true,
            'message' => 'Successfully followed',
        ]);
    }

    /**
     * Отписаться от пользователя
     *
     * @param Request $request
     * @param User $user
     *
     * @return Response
     */
    public function unfollow(Request $request, User $user): Response
    {
        $request->user()->follows()->detach($user->id);
        event(new ActivityEvent($request->user(), Setting::UNFOLLOW));

        return response([
            'status' => true,
            'message' => 'You successfully unfollowed from user!',
        ]);
    }

    /**
     * Принудительно отписать от себя пользователя с возвратом потраченных им средств
     *
     * @param ForceUnfollowRequest $request
     * @param User $user
     *
     * @return Response
     * @throws Throwable
     */
    public function forceUnfollow(ForceUnfollowRequest $request, User $user): Response
    {
        $followAmount = $request->user()->followers()->whereUserId($user->id)->value('amount') ?? 0;
        throw_if($followAmount > $request->user()->currentBalance(), new NotEnoughCoinsException());

        DB::beginTransaction();
        try {
            $request->user()->transactions()->create([
                'type' => config('balance.credit.force_unfollow'),
                'amount' => $followAmount ?? null,
                'target_id' => $user->id
            ]);
            $user->transactions()->create([
                'type' => config('balance.debit.force_unfollow'),
                'amount' => $followAmount ?? null,
                'who_id' => $request->user()->id
            ]);
            $request->user()->followers()->detach($user->id);
            $request->user()->update(['rating'=> DB::raw('rating-0.1')]);

            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();

            return response([
                'status' => false,
                'error' => 'Transaction failed',
                'errors' => (object)[
                    'transaction' => $exception->getMessage()
                ]
            ]);
        }
        event(new ActivityEvent($request->user(), Setting::FORCE_UNFOLLOW));

        return response([
            'status' => true,
            'message' => 'User successfully unfollowed from you',
        ]);
    }

    /**
     * Вывести список подписчиков
     *
     * @param PaginationRequest $request
     *
     * @return FollowsCollection
     */
    public function getFollowers(PaginationRequest $request): FollowsCollection
    {
        return new FollowsCollection(
            $request->user()
                ->followers()
                ->latest()
                ->paginate($request->per_page ?? 12)
        );
    }

    /**
     * Вывести список подписок
     *
     * @param PaginationRequest $request
     *
     * @return FollowsCollection
     */
    public function getFollows(PaginationRequest $request): FollowsCollection
    {
        return new FollowsCollection(
            $request->user()
                ->follows()
                ->latest()
                ->paginate($request->per_page ?? 12)
        );
    }

    /**
     * Вывод информации о пользователе
     *
     * @param ShowUserProfileRequest $request
     * @param User $user
     *
     * @return UserResource|AuthResource
     */
    public function getUserProfile(ShowUserProfileRequest $request, User $user): AuthResource|UserResource
    {
        $userWithRelations = $user->load([
            'goals',
            'posts',
            'career',
            'education',
            'students',
            'followers',
            'achievements',
            'activities',
            'mentorSkills' => fn($q) => $q->with(['nestedUserSkills', 'baseSkill']),
            'hobbySkills',
            'reports',
        ]);

        if ($request->user()->id === $user->id) {
            return new AuthResource($userWithRelations);
        } else {
            $userWithRelations->already_follow = $request->user()->follows()->exists($user);
            $userWithRelations->already_mentor = $request->user()->mentors()->exists($user);
            $userWithRelations->append(['isAlreadyFollow', 'isAlreadyMentor']);

            return new UserResource($userWithRelations);
        }
    }
}
