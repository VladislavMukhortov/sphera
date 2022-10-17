<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\ActivityEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\{AchievementResource, AchievementsCollection};
use App\Models\{Achievement, Setting};
use App\Http\Requests\Api\{DestroyAchievementRequest,
    GetAchievementsRequest,
    StoreAchievementRequest,
    UpdateAchievementRequest};
use Illuminate\Http\Response;

class AchievementController extends Controller
{
    /**
     * Возвращает список достижений
     *
     * @param GetAchievementsRequest $request
     *
     * @return AchievementsCollection
     */
    public function index(GetAchievementsRequest $request): AchievementsCollection
    {
        $autoAchievementsCount = $request->user()->achievements()->where('auto', true)->count();
        $manualAchievementsCount = $request->user()->achievements()->where('auto', false)->count();

        $achievements = $request->user()->achievements()->with(['goal', 'skill']);
        $request->whenHas('auto', fn($key) => $achievements->where('auto', filter_var($key, FILTER_VALIDATE_BOOLEAN)));

        return new AchievementsCollection(
            $achievements->paginate($request->per_page ?? 12),
            $autoAchievementsCount,
            $manualAchievementsCount
        );
    }

    /**
     * Добавляет новое достижение
     *
     * @param StoreAchievementRequest $request
     *
     * @return AchievementResource
     */
    public function store(StoreAchievementRequest $request): AchievementResource
    {
        $newAchievement = $request->user()->achievements()->create($request->validated() + ['auto' => false]);
        event(new ActivityEvent($request->user(), Setting::NEW_ACHIEVEMENT));

        return new AchievementResource($newAchievement->load(['goal', 'skill']));
    }

    /**
     * Возвращает информацию о достижении
     *
     * @param Achievement $achievement
     *
     * @return AchievementResource
     */
    public function show(Achievement $achievement): AchievementResource
    {
        return new AchievementResource($achievement->load(['goal', 'skill']));
    }

    /**
     * Обновление ручного достижения
     *
     * @param UpdateAchievementRequest $request
     * @param Achievement $achievement
     *
     * @return AchievementResource
     */
    public function update(UpdateAchievementRequest $request, Achievement $achievement): AchievementResource
    {
        $achievement->update($request->validated());

        return new AchievementResource($achievement->load(['goal', 'skill']));
    }

    /**
     * Удаляет достижение
     *
     * @param DestroyAchievementRequest $request
     * @param Achievement $achievement
     *
     * @return Response
     */
    public function destroy(DestroyAchievementRequest $request, Achievement $achievement): Response
    {
        return response([
            'status' => (bool)$achievement->delete(),
            'message' => 'Success delete',
        ]);
    }
}
