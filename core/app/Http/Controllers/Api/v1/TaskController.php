<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\{ActivityEvent, GoalFinishedEvent, GoalUpdatedEvent};
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\{DestroyTaskRequest, GetTaskRequest, StoreTaskRequest, UpdateTaskRequest};
use App\Http\Resources\{TaskResource, TasksCollection};
use App\Models\{Goal, Setting, Task};
use Illuminate\Http\Response;

class TaskController extends Controller
{
    /**
     * Возвращает список задач выбранной цели
     *
     * @param GetTaskRequest $request
     * @param Goal           $goal
     *
     * @return TasksCollection
     */
    public function index(GetTaskRequest $request, Goal $goal): TasksCollection
    {
        return new TasksCollection(
            $goal->tasks()
                ->when($request->has('is_completed'), fn($q) => $q->where('is_completed', $request->is_completed))
                ->when($request->has('from'), fn($q) => $q->where('created_at', '>=', $request->from))
                ->when($request->has('to'), fn($q) => $q->where('created_at', '<=', $request->to))
                ->when($request->has('start_at'), fn($q) => $q->where('start_at', $request->start_at))
                ->when($request->has('deadline_at'), fn($q) => $q->where('deadline_at', $request->deadline_at))
                ->orderBy('created_at', 'desc')
                ->paginate((int) $request->per_page ?? 20)
        );
    }

    /**
     * Добавляет новую задачу к переданной цели
     *
     * @param StoreTaskRequest $request
     * @param Goal             $goal
     *
     * @return TaskResource
     */
    public function store(StoreTaskRequest $request, Goal $goal): TaskResource
    {
        $newTask = $goal->tasks()->create($request->validated());
        event(new GoalUpdatedEvent($goal->withoutRelations()));

        return new TaskResource($newTask);
    }

    /**
     * Возвращает выбранную задачу у переданной цели
     *
     * @param Goal $goal
     * @param Task $task
     *
     * @return TaskResource
     */
    public function show(Goal $goal, Task $task): TaskResource
    {
        return new TaskResource($task);
    }

    /**
     * Обновляет переданную задачу у выбранной цели
     *
     * @param UpdateTaskRequest $request
     * @param Goal              $goal
     * @param Task              $task
     *
     * @return TaskResource
     */
    public function update(UpdateTaskRequest $request, Goal $goal, Task $task): TaskResource
    {
        $task->update($request->validated());
        $task->load('goal');

        if ($task->goal->progress === 100) {
            $task->goal->update(['status' => Goal::STATUSES[Goal::STATUS_COMPLETE]]);
            event(new GoalFinishedEvent($goal->withoutRelations()));
        } else event(new GoalUpdatedEvent($goal->withoutRelations()));

        if ($request->is_completed) {
            event(new ActivityEvent($request->user(), Setting::GOAL_PROGRESS));
        }

        return new TaskResource($task);
    }

    /**
     * Удаляет переданную задачу у выбранной цели
     *
     * @param DestroyTaskRequest $request
     * @param Goal               $goal
     * @param Task               $task
     *
     * @return Response
     */
    public function destroy(DestroyTaskRequest $request, Goal $goal, Task $task): Response
    {
        return response([
            'status' => (bool)$task->delete(),
            'message' => 'Success delete',
        ]);
    }
}
