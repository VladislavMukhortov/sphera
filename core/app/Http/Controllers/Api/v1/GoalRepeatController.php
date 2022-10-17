<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\{ActivityEvent, GoalFinishedEvent, GoalUpdatedEvent};
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\{DestroyGoalRepeatRequest, StoreGoalRepeatRequest, UpdateGoalRepeatRequest};
use App\Http\Resources\{GoalRepeatResource, GoalResource, GoalsRepeatsCollection};
use App\Models\{Goal, GoalRepeat, Setting};
use Illuminate\Http\Response;

class GoalRepeatController extends Controller
{
    /**
     * Возвращает детализацию прогресса по цели
     *
     * @param Goal $goal
     *
     * @return GoalsRepeatsCollection
     */
    public function index(Goal $goal): GoalsRepeatsCollection
    {
        return new GoalsRepeatsCollection($goal->repeats()->orderByDesc('created_at')->get());
    }

    /**
     * Добавляет новый прогресс к цели
     *
     * @param StoreGoalRepeatRequest $request
     * @param Goal                   $goal
     *
     * @return GoalResource
     */
    public function store(StoreGoalRepeatRequest $request, Goal $goal): GoalResource
    {
        $goal->repeats()->create($request->validated());

        if ($goal->progress === 100) {
            $goal->update(['status' => Goal::STATUSES[Goal::STATUS_COMPLETE]]);
            event(new GoalFinishedEvent($goal->withoutRelations()));
        } else event(new GoalUpdatedEvent($goal->withoutRelations()));
        event(new ActivityEvent($request->user(), Setting::GOAL_PROGRESS));

        return new GoalResource($goal->load('repeats'));
    }

    /**
     * Обновляет запись прогресса по цели
     *
     * @param UpdateGoalRepeatRequest   $request
     * @param Goal                      $goal
     * @param GoalRepeat                $repeat
     *
     * @return GoalRepeatResource
     */
    public function update(UpdateGoalRepeatRequest $request, Goal $goal, GoalRepeat $repeat): GoalRepeatResource
    {
        $repeat->update($request->validated());

        if ($goal->progress === 100) {
            $goal->update(['status' => Goal::STATUSES[Goal::STATUS_COMPLETE]]);
            event(new GoalFinishedEvent($goal->withoutRelations()));
        } else event(new GoalUpdatedEvent($goal->withoutRelations()));

        return new GoalRepeatResource($repeat->load('goal'));
    }

    /**
     * Удаляет пункт прогресса у выбранной цели
     *
     * @param DestroyGoalRepeatRequest  $request
     * @param Goal                      $goal
     * @param GoalRepeat                $repeat
     *
     * @return Response
     */
    public function destroy(DestroyGoalRepeatRequest $request, Goal $goal, GoalRepeat $repeat): Response
    {
        return response([
            'status' => (bool)$repeat->delete(),
            'message' => 'Success delete',
        ]);
    }
}
