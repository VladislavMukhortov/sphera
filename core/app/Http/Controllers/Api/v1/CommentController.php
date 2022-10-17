<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\{ActivityEvent, NewGoalCommentEvent, NewReportCommentEvent};
use App\Http\Controllers\Controller;
use App\Models\{Comment, Goal, Report, Setting};
use App\Http\Resources\{CommentResource, CommentsCollection};
use App\Http\Requests\Api\{DestroyCommentRequest,
    PaginationRequest,
    StoreCommentRequest,
    UpdateCommentRequest
};
use Illuminate\Http\Response;

class CommentController extends Controller
{
    /**
     * Возвращает список комментариев
     *
     * @param PaginationRequest $request
     *
     * @return CommentsCollection
     */
    public function index(PaginationRequest $request): CommentsCollection
    {
        return new CommentsCollection(
            $request->user()->comments()->paginate($request->per_page ?? 12)
        );
    }

    /**
     * Добавляет новый комментарий
     *
     * @param StoreCommentRequest $request
     *
     * @return CommentResource
     */
    public function store(StoreCommentRequest $request): CommentResource
    {
        $commentTarget = match (true) {
            $request->has('goal_id') => Goal::find($request->goal_id),
            $request->has('report_id') => Report::find($request->report_id),
        };

        if ($request->user()->id !== $commentTarget->user_id) {
            switch ($commentTarget) {
                case $commentTarget instanceof Goal:
                    event(new NewGoalCommentEvent($commentTarget));
                    break;
                case $commentTarget instanceof Report:
                    event(new NewReportCommentEvent($commentTarget));
                    break;
            }
        }

        event(
            new ActivityEvent(
                $request->user(),
                $request->has('parent_id') ? Setting::NEW_COMMENT_ANSWER : Setting::NEW_COMMENT
            )
        );

        return new CommentResource(
            $commentTarget->comments()
                ->create($request->validated() + ['user_id' => $request->user()->id])->load('user')
        );
    }

    /**
     * Отобразить комментарий
     *
     * @param Comment $comment
     *
     * @return CommentResource
     */
    public function show(Comment $comment): CommentResource
    {
        return new CommentResource($comment->load(['user', 'replies']));
    }

    /**
     * Обновить комментарий
     *
     * @param UpdateCommentRequest $request
     * @param Comment $comment
     *
     * @return CommentResource
     */
    public function update(UpdateCommentRequest $request, Comment $comment): CommentResource
    {
        $comment->update($request->validated());

        return new CommentResource($comment->load('user'));
    }

    /**
     * Удалить комментарий
     *
     * @param DestroyCommentRequest $request
     * @param Comment $comment
     *
     * @return Response
     */
    public function destroy(DestroyCommentRequest $request, Comment $comment): Response
    {
        return response([
            'status' => (bool)$comment->delete(),
            'message' => 'Success delete',
        ]);
    }
}
