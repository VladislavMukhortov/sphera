<?php

namespace App\Http\Controllers\Api\v1\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\{DestroyUserSkillRequest,
    GetUserSkillsRequest,
    StoreHobbySkillRequest,
    StoreMentorSkillRequest};
use App\Http\Resources\{BaseResource, Profile\UserSkillCollection, SkillsCollection};
use App\Models\{Profile\UserSkill, Skill};
use Illuminate\Http\Response;

class UserSkillController extends Controller
{
    /**
     * Возвращает список навыков текущего пользователя
     *
     * @param GetUserSkillsRequest $request
     *
     * @return UserSkillCollection
     */
    public function index(GetUserSkillsRequest $request): UserSkillCollection
    {
        $skills = $request->mentor
            ? $request->user()->mentorSkills()->with(['baseSkill', 'nestedUserSkills'])->get()
            : $request->user()->hobbySkills;

        return new UserSkillCollection($skills);
    }

    /**
     * Возвращает список всех дефолтных навыков
     *
     * @return SkillsCollection
     */
    public function list(): SkillsCollection
    {
        return new SkillsCollection(Skill::all());
    }

    /**
     * Добавление менторской категории/детали по категории пользователю
     *
     * @param StoreMentorSkillRequest $request
     *
     * @return BaseResource
     */
    public function storeMentorSkill(StoreMentorSkillRequest $request): BaseResource
    {
        $newSkill = $request->user()->skills()->firstOrCreate($request->only('skill_id'), ['mentor' => true]);

        if ($request->title) {
            $newSkill = $newSkill->nestedUserSkills()->firstOrCreate([
                'parent_id' => $newSkill->id,
                'title' => $request->title
            ], [
                'user_id' => $request->user()->id,
                'mentor' => true,
            ]);
        }

        return new BaseResource([
            'message' => $newSkill->wasRecentlyCreated ? 'Mentor skill stored successfully' : 'Mentor skill already exists',
            'data' => [
                'id' => $newSkill->id,
            ],
        ]);
    }

    /**
     * Добавление увлечения пользователю
     *
     * @param StoreHobbySkillRequest $request
     *
     * @return BaseResource
     */
    public function storeUserHobby(StoreHobbySkillRequest $request): BaseResource
    {
        $newHobbySkill = $request->user()->skills()->firstOrCreate($request->validated() + ['mentor' => false]);

        return new BaseResource([
            'message' => $newHobbySkill->wasRecentlyCreated ? 'Hobby stored successfully' : 'Hobby already exists',
            'data' => ['id' => $newHobbySkill->id],
        ]);
    }

    /**
     * Удаляет навык пользователя
     *
     * @param DestroyUserSkillRequest $request
     * @param UserSkill $userSkill
     *
     * @return Response
     */
    public function destroy(DestroyUserSkillRequest $request, UserSkill $userSkill): Response
    {
        return response([
            'status' => (bool)$userSkill->delete(),
            'message' => 'Success delete',
        ]);
    }
}
