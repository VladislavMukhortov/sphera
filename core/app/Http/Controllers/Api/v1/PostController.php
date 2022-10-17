<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\{ActivityEvent, FollowSearchMentorEvent};
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\{DestroyFavoriteRequest,
    DestroyPostRequest,
    GetPostRequest,
    PaginationRequest,
    StorePostRequest,
    UpdatePostRequest};
use App\Http\Resources\{PostResource, PostsCollection};
use App\Models\{Goal, Post, Setting, Tag, UserNotification};
use Illuminate\Http\{Request, Response};

class PostController extends Controller
{
    /**
     * Возвращает ленту, с фильтрацией общая/личная
     *
     * @param GetPostRequest $request
     * @deprecated Пока отключен.
     * @return PostsCollection
     */
    public function list(GetPostRequest $request): PostsCollection
    {
        if ($request->has('search')) {
            preg_match_all('(#+[\w(_)]+)u', $request->search, $tags, PREG_SET_ORDER);
            $request->merge(['title' => trim(preg_replace('(#+[\w(_)]+)u', '', $request->search))]);

            if ($tags && $tags = array_map(fn($q) => preg_replace('/[#_]/', ' ', $q[0]), $tags)) {
                $request->merge(['tags'  => array_map('trim', $tags)]);
            }
        }

        $posts = Post::with([
            'tags',
            'goal',
            'user' => fn($q) => $q->with('settings')
        ])
            ->when($request->has('only_own'), fn($q) => $q->whereUserId($request->user()->id))
            ->when($request->has('uuid'), fn($q) => $q->whereHas('user', fn($qr) => $qr->whereUuid($request->uuid)))
            ->when($request->has('to_date'),
                fn($q) => $q->whereBetween('created_at', [
                    now()->parse($request->from_date),
                    now()->parse($request->to_date)->endOfDay()
                ])
            )
            ->when($request->has('type'), fn($q) => $q->whereType($request->type))
            ->when(
                $request->has('title'),
                fn($q) => $q->whereHas('goal', fn($qr) => $qr->where('title', 'like', "%$request->title%"))
            )
            ->when(
                $request->has('tags'),
                fn($q) => $q->whereHas(
                    'goal',
                    fn($qr) => $qr->whereHas(
                        'tags',
                        fn($query) => $query->where(function ($query) use ($request, &$tag) {
                            foreach ($request->tags as $tag) {
                                $query->orWhere('title', 'like', "%$tag%");
                            }
                        })
                    )
                )
            )
            ->where(fn($q) => $q->whereHas(
                'user',
                fn($qr) => $qr->whereHas(
                    'settings',
                    fn($query) => $query->whereSetting('post_visibility')->whereValue('all')
                )
            ))
            ->paginate($request->per_page ?? 25);

        return new PostsCollection($posts);
    }

    /**
     * Возвращает публикации для раздела "Подписки" ленты (посты подписок)
     *
     * @param PaginationRequest $request
     *
     * @return PostsCollection
     */
    public function follows(PaginationRequest $request): PostsCollection
    {
        $followsIds = $request->user()->follows->pluck('id')->toArray();
        $posts = Post::with([
            'tags',
            'goal',
            'user' => fn($q) => $q->with('settings')
        ])
            ->where(fn($q) => $q->whereHas(
                'user',
                fn($qr) => $qr->whereHas(
                    'settings',
                    fn($query) => $query->whereSetting('post_visibility')->whereValue('all')
                )
                ->whereIn('id', $followsIds)
            ))
            ->paginate($request->per_page ?? 25);

        return new PostsCollection($posts);
    }

    /**
     * Возвращает публикации для раздела "Общее" ленты (рекомендации, основанные на увлечениях юзера)
     *
     * @param PaginationRequest $request
     *
     * @return PostsCollection
     */
    public function common(PaginationRequest $request): PostsCollection
    {
        $skillsIds = $request->user()->skills->pluck('skill_id')->toArray();
        $posts = Post::with([
            'tags',
            'goal',
            'user' => fn($q) => $q->with('settings')
        ])
            ->where(fn($q) => $q->whereHas(
                'user',
                fn($qr) => $qr->whereHas(
                    'settings',
                    fn($query) => $query->whereSetting('post_visibility')->whereValue('all')
                )
            ))
            ->where(fn($q) => $q->whereHas(
                'goal',
                fn($qr) => $qr->whereIn('skill_id', $skillsIds)
            ))
            ->paginate($request->per_page ?? 25);

        return new PostsCollection($posts);
    }

    /**
     * Возвращает публикации для раздела "Избранное" ленты (сохраненные посты)
     *
     * @param PaginationRequest $request
     *
     * @return PostsCollection
     */
    public function favorites(PaginationRequest $request): PostsCollection
    {
        $favoritesIds = $request->user()->favorites->pluck('post_id')->toArray();
        $posts = Post::with([
            'tags',
            'goal',
            'user' => fn($q) => $q->with('settings')
        ])
            ->where(fn($q) => $q->whereHas(
                'user',
                fn($qr) => $qr->whereHas(
                    'settings',
                    fn($query) => $query->whereSetting('post_visibility')->whereValue('all')
                )
            ))
            ->whereIn('id', $favoritesIds)
            ->paginate($request->per_page ?? 25);

        return new PostsCollection($posts);
    }

    /**
     * Создать публикацию
     *
     * @param StorePostRequest $request
     * @param Goal $goal
     * @param UserNotification $notification
     *
     * @return PostResource
     */
    public function store(StorePostRequest $request, Goal $goal, UserNotification $notification): PostResource
    {
        if ($request->tags && in_array(Tag::SEARCH_MENTOR, $request->tags)) {
            $type = ['type' => Post::TYPES[Post::MENTORING]];
            $notification->storeSearchMentor($goal, $request->amount);
            event(
                new FollowSearchMentorEvent($goal),
                new ActivityEvent($request->user(), Setting::NEW_REQUEST_FOR_MENTOR)
            );
        }

        $post = $request->user()->posts()->create(
            $request->validated() + ['goal_id' => $goal->id] + ($type ?? ['type' => Post::TYPES[Post::GOAL]])
        );
        $request->whenHas('tags', fn() => $post->tags()->attach($request->tags));

        return new PostResource($post->load(['tags', 'goal']));
    }

    /**
     * Обновить публикацию
     *
     * @param UpdatePostRequest $request
     * @param Post $post
     * @param UserNotification $notification
     *
     * @return PostResource
     */
    public function update(UpdatePostRequest $request, Post $post, UserNotification $notification): PostResource
    {
        if ($tags = $request->tags) {
            $post->tags()->sync($tags);

            if (in_array(Tag::SEARCH_MENTOR, $tags)) {
                $type = ['type' => Post::TYPES[Post::MENTORING]];
                $notification->storeSearchMentor($post->goal)->update($request->only('amount'));
                event(
                    new FollowSearchMentorEvent($post->goal),
                    new ActivityEvent($request->user(), Setting::NEW_REQUEST_FOR_MENTOR)
                );
            } else {
                $type = [];
            }
        }
        $post->update($request->validated() + ($type ?? []));

        return new PostResource($post->load(['goal', 'tags']));
    }

    /**
     * Удалить публикацию
     *
     * @param DestroyPostRequest $request
     * @param Post $post
     * @param UserNotification $notification
     *
     * @return Response
     */
    public function destroy(DestroyPostRequest $request, Post $post, UserNotification $notification): Response
    {
        if ($status = $post->delete()) {
            $notification->storeSearchMentor($post->goal)->delete();
        }

        return response([
            'status' => (bool)$status,
            'message' => 'Success delete',
        ]);
    }

    /**
     * Добавляет пост в избранное
     *
     * @param Request $request
     * @param Post $post
     *
     * @return Response
     */
    public function favoriteAdd(Request $request, Post $post): Response
    {
        $favorite = $request->user()->favorites()->firstOrCreate(['post_id' => $post->id]);

        return response([
            'status' => (bool)$favorite->wasRecentlyCreated,
            'message' => $favorite->wasRecentlyCreated ? 'Successfully added to favorite' : 'Post already exists in favorites',
        ]);
    }

    /**
     * Удалить пост из избранного
     *
     * @param DestroyFavoriteRequest $request
     * @param Post $post
     *
     * @return Response
     */
    public function favoriteRemove(DestroyFavoriteRequest $request, Post $post): Response
    {
        return response([
            'status' => (bool)$request->user()->favorites()->wherePostId($post->id)->delete(),
            'message' => 'Success delete',
        ]);
    }
}
