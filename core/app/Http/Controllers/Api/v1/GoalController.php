<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\{ActivityEvent, GoalFinishedEvent, GoalUpdatedEvent};
use App\Models\{Goal, Setting, Skill};
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\{GetGoalRequest, DestroyGoalRequest, StoreGoalRequest, UpdateGoalRequest};
use App\Http\Resources\{GoalResource, GoalsCollection};
use Illuminate\Http\Response;
class GoalController extends Controller
{
    /**
     * Возвращает список целей текущего пользователя
     *
     * @param GetGoalRequest $request
     *
     * @return GoalsCollection
     */
    public function index(GetGoalRequest $request): GoalsCollection
    {
        preg_match_all('(#+[\w(_)]+)u', $request->title, $categoryNames, PREG_SET_ORDER);
        if ($categoryNames && $categoryNames = array_map(fn($q) => preg_replace('/[#_]/', ' ', $q[0]), $categoryNames)) {
            $trimmedNames = array_map('trim', $categoryNames);

            if ($skillIds = Skill::whereIn('title', $trimmedNames)->pluck('id')->toArray()) {
                $request->merge([
                    'title'     => preg_replace('(#+[\w(_)]+)u', '', $request->title),
                    'skill_ids' => $skillIds
                ]);
            }
        }

        $goals = $request->user()->goals()->with(['repeats', 'tasks'])
            ->when($request->has('skill_ids'), fn($q) => $q->whereIn('skill_id', $request->skill_ids))
            ->when($request->has('title'), fn($q) => $q->where('title', 'like', "%$request->title%"))
            ->when($request->has('type'), fn($q) => $q->where('type', $request->type))
            ->when($request->has('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->has('from'), fn($q) => $q->where('created_at', '>=', $request->from))
            ->when($request->has('to'), fn($q) => $q->where('created_at', '<=', $request->to))
            ->when($request->has('start_at'), fn($q) => $q->where('start_at', $request->start_at))
            ->when($request->has('deadline_at'), fn($q) => $q->where('deadline_at', $request->deadline_at))
            ->paginate((int)$request->per_page ?? 20);

        $sortedGoals = $goals->getCollection()->sortByDesc(function ($goal) {
            return $goal->type == Goal::TYPES[Goal::TYPE_REPEAT]
                ? $goal->repeats()->latest('updated_at')->value('updated_at')
                : $goal->tasks()->latest('updated_at')->value('updated_at');
        })->values();
        $goals->setCollection($sortedGoals);

        return new GoalsCollection($goals);
    }

    /**
     * Добавляет новую цель текущему пользователю, уведомляет менторов/студентов
     *
     * @param StoreGoalRequest $request
     *
     * @return GoalResource
     */
    public function store(StoreGoalRequest $request): GoalResource
    {
        $goal = $request->user()->goals()->create($request->validated());
        if ($request->type === Goal::TYPES[Goal::TYPE_REPEAT]) {
            $goal->option()->create($request->only(['action_button', 'unit', 'target_count']));
        }
        event(new ActivityEvent($request->user(), Setting::NEW_GOAL));

        return new GoalResource($goal->load(['repeats', 'option', 'comments']));
    }

    /**
     * Возвращает выбранную цель текущего пользователя
     *
     * @param Goal $goal
     *
     * @return GoalResource
     */
    public function show(Goal $goal): GoalResource
    {
        return new GoalResource($goal->load(['repeats', 'option', 'comments']));
    }

    /**
     * Обновляет выбранную цель текущего пользователя
     *
     * @param UpdateGoalRequest $request
     * @param Goal $goal
     *
     * @return GoalResource
     */
    public function update(UpdateGoalRequest $request, Goal $goal): GoalResource
    {
        if ($request->status === Goal::STATUSES[Goal::STATUS_PAUSED]) {
            $pauseData = ['paused_at' => now()->startOfDay()];
        } elseif ($goal->paused_at && $request->status && $request->status != Goal::STATUSES[Goal::STATUS_PAUSED]) {
            $pauseData = [
                'deadline_at' => now()->addDays($goal->deadline_at->diffInDays($goal->paused_at))->startOfDay(),
                'paused_at' => null,
            ];
        }
        $goal->update($request->validated() + ($pauseData ?? []));

       $request->status === Goal::STATUSES[Goal::STATUS_COMPLETE]
           ? event(new GoalFinishedEvent($goal->withoutRelations()))
           : event(new GoalUpdatedEvent($goal->withoutRelations()));

        return new GoalResource($goal);
    }

    /**
     * Удаляет выбранную цель текущего пользователя
     *
     * @param DestroyGoalRequest $request
     * @param Goal $goal
     *
     * @return Response
     */
    public function destroy(DestroyGoalRequest $request, Goal $goal): Response
    {
        return response([
            'status' => (bool)$goal->delete(),
            'message' => 'Success delete',
        ]);
    }
}
