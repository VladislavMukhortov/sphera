<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\FeedbackCreateEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetFeedbackRequest;
use App\Http\Requests\Api\StoreFeedbackRequest;
use App\Http\Requests\Api\UpdateFeedbackRequest;
use App\Http\Resources\BaseResource;
use App\Http\Resources\FeedbackResource;
use App\Http\Resources\FeedbacksCollection;
use App\Http\Resources\GoalResource;
use App\Models\Feedback;
use App\Models\Goal;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Получить все отзывы по goal_id.
     *
     * @param GetFeedbackRequest $request
     * @return FeedbacksCollection
     */
    public function index(GetFeedbackRequest $request): FeedbacksCollection
    {
        $feedbacks = Feedback::where('goal_id', $request->get('goal_id'))
            ->get();

        return new FeedbacksCollection($feedbacks);
    }

    /**
     * Создать новый отзыв
     *
     * @param  StoreFeedbackRequest  $request
     * @param  Goal  $goal
     * @return FeedbackResource
     */
    public function store(StoreFeedbackRequest $request, Goal $goal): FeedbackResource
    {
        $feedback = Feedback::create($request->validated());
        event(new FeedbackCreateEvent($feedback));
        return new FeedbackResource($feedback);
    }

    /**
     * Показ детальной страницы отзыва
     *
     * @param  Feedback  $feedback
     * @return FeedbackResource
     */
    public function show(Feedback $feedback): FeedbackResource
    {
        return new FeedbackResource($feedback->load(['goal' => fn($q) => $q->with(['user', 'mentor'])]));
    }

    /**
     * Обновление отзыва
     *
     * @param  UpdateFeedbackRequest $request
     * @param  Feedback $feedback
     * @return FeedbackResource
     */
    public function update(UpdateFeedbackRequest $request, Feedback $feedback): FeedbackResource
    {
        $feedback->update($request->validated());
        return new FeedbackResource($feedback->load(['goal' => fn($q) => $q->with(['user', 'mentor'])]));
    }

    /**
     * Удаление отзыва
     *
     * @param  Feedback $feedback
     * @return BaseResource
     */
    public function destroy(Feedback $feedback): BaseResource
    {
        return new BaseResource([
            'status'  => $feedback->delete(),
            'message' => "Success delete",
        ]);
    }
}
