<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Http\Requests\Api\{AchievementSearchRequest, GlobalSearchRequest};
use App\Http\Resources\{AchievementsCollection, GoalsCollection, PostsCollection, UsersCollection};
use App\Models\{Achievement, Goal, Post, User};
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Builder;

class GlobalSearchController extends Controller
{
    /**
     * Пагинация
     */
    protected const PER_PAGE = 4;

    /**
     * Основная строка для поиска, без тегов
     * @var string
     */
    protected string $text;

    /**
     * Теги, если были указаны в строке поиска
     * @var string|null
     */
    protected ?string $tags = null;

    /**
     * Парсинг строки поиска
     *
     * @param GlobalSearchRequest $request
     */
    public function __construct(GlobalSearchRequest $request)
    {
        $this->text = trim(preg_replace('(#+[\w(_)]+)u', '', $request->text));

        preg_match_all('(#+[\w(_)]+)u', $request->text, $tags, PREG_SET_ORDER);
        if ($tags && $tags = array_map(fn($q) => preg_replace('/[#_]/', ' ', $q[0]), $tags)) {
            $this->tags = implode('|', array_map('trim', $tags));
        }
    }

    /**
     * Глобальный поиск по пользователям
     *
     * @return UsersCollection
     */
    public function users(): UsersCollection
    {
        return new UsersCollection(
            User::query()->with(['career', 'settings', 'goals', 'follows'])
                //не забыть middle name
                ->whereRaw("concat(first_name, ' ', last_name) like '%$this->text%'")
                ->orWhere(
                    fn($q) => $q->whereHas(
                        'career',
                        fn($qr) => $qr
                            ->join(
                                DB::raw(
                                    "(SELECT MAX(date_start) as date FROM `user_career` GROUP BY user_id) as last_updates"
                                ),
                                'last_updates.date',
                                '=',
                                'user_career.date_start'
                            )
                            ->where('position_name', 'like', "%$this->text%")
                    )
                )
                //не забыть geo
//                ->orWhere('geo', 'like', "%$this->text%")
                ->searchVisible()
                ->paginate(self::PER_PAGE));
    }

    /**
     * Глобальный поиск по целям
     *
     * @param GlobalSearchRequest $request
     *
     * @return GoalsCollection
     */
    public function goals(GlobalSearchRequest $request): GoalsCollection
    {
        $userId = false;
        if (auth('sanctum')->user()) {
            $userId = auth('sanctum')->user()->id;
        }
        $studentsId = $request->user()->students->unique()->pluck('id')->toArray();

        $goalsIds = Goal::search($this->text)
            ->get()
            ->pluck('id');

        $mentorsIds = User::search($this->text)
            ->get()
            ->pluck('id');

        return new GoalsCollection(
            Goal::with(['user' => fn ($q) => $q->with(['followers'])])->where(fn ($q) =>
                $q->whereIn('id', $goalsIds)
                    ->orWhereIn('mentor_id', $mentorsIds)
            )->where(fn ($q) =>
                $q->where('can_view', 'all')
            )->orWhere(fn ($q) =>
                $q->where('can_view', 'mentors')
                ->where('mentor_id', $userId)
            )->orWhere(fn ($q) =>
                $q->where('can_view', 'followers')
                ->whereHas(
                    'user',
                    fn($q) => $q->whereHas(
                        'followers',
                        fn ($q) => $q->where('user_id', $userId)
                    )->where('user_id', $userId)
                )
            )->simplePaginate(10)
        );
    }

    /**
     * Глобальный поиск публикаций по всей ленте
     *
     * @param GlobalSearchRequest $request
     *
     * @return PostsCollection
     */
    public function posts(GlobalSearchRequest $request): PostsCollection
    {
        $studentsId = $request->user()->students->unique()->pluck('id')->toArray();

        return new PostsCollection(
            Post::query()->with(['goal', 'user' => fn($q) => $q->with('settings')])
                ->whereHas(
                    'user',
                    fn($q) => $q->whereHas(
                        'settings',
                        fn($qr) => $qr
                            ->where(fn($qr) => $qr->whereSetting('post_visibility')->whereValue('all'))
                            ->orWhere(fn($qr) => $qr->whereSetting('post_visibility')->whereValue('mentors')->whereIn('user_id', $studentsId))
                    )
                )
                ->where(fn($q) => $q
                    ->whereHas('goal',
                        fn($q) => $q->where('title', 'like', "%$this->text%")
                            ->orWhereHas('mentor', fn($q) => $q->whereRaw("concat(first_name, ' ', last_name, ' ', middle_name) like '%$this->text%'"))
                            ->orWhereHas('tasks', fn($q) => $q->where('title', 'like', "%$this->text%"))
                            ->when($this->tags, fn($q) => $q->orWhereHas('skill', fn($qr) => $qr->where('title', 'rlike', "$this->tags")))
                    )
                    ->orWhereHas('user', fn($q) => $q->whereRaw("concat(first_name, ' ', last_name, ' ', middle_name) like '%$this->text%'"))
                )
                ->paginate(self::PER_PAGE));
    }

    /**
     * Глобальный поиск публикаций по личной ленте
     *
     * @param GlobalSearchRequest $request
     *
     * @return PostsCollection
     */
    public function myPosts(GlobalSearchRequest $request): PostsCollection
    {
        return new PostsCollection($request->user()->posts()->with('goal')
            ->whereHas('goal', fn($q) => $q
                ->where('title', 'like', "%$this->text%")
                ->orWhereHas('mentor', fn($q) => $q->whereRaw("concat(first_name, ' ', last_name) like '%$this->text%'"))
                ->orWhereHas('tasks', fn($q) => $q->where('title', 'like', "%$this->text%"))
                ->when($this->tags, fn($q) => $q->orWhereHas('skill', fn($qr) => $qr->where('title', 'rlike', "$this->tags")))
            )
            ->paginate(self::PER_PAGE));
    }

    /**
     * Глобальный поиск по достижениям
     *
     * @param AchievementSearchRequest $request
     * @return AchievementsCollection
     */
    public function achievement(AchievementSearchRequest $request): AchievementsCollection
    {
        $userId = false;
        if (auth('sanctum')->user()) {
            $userId = auth('sanctum')->user()->id;
        }

        return new AchievementsCollection(
            Achievement::query()->with(['goal' => fn($q) => $q->with(['user' => fn($q) => $q->with(['settings', 'follows'])])])
                ->where(fn($q) => $q->where('title', 'like', "%$this->text%")
                    ->orwhere('description', 'like', "%$this->text%")
                )
                ->searchVisible()
                ->paginate(self::PER_PAGE));
    }
}
