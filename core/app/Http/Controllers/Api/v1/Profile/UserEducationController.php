<?php

namespace App\Http\Controllers\Api\v1\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\{DestroyUserEducationRequest, StoreUserEducationRequest, UpdateUserEducationRequest};
use App\Http\Resources\BaseResource;
use App\Http\Resources\Profile\{UserEducationResource, UserEducationsCollection};
use App\Models\Profile\UserEducation;
use App\Services\FileService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserEducationController extends Controller
{
    /**
     * Сервисный слой для работы с файлами
     *
     * @var FileService
     */
    public FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * Вывести все записи об образовании пользователя
     *
     * @return UserEducationsCollection
     */
    public function index(): UserEducationsCollection
    {
        return new UserEducationsCollection(
            Auth::guard('sanctum')->user()->education
        );
    }

    /**
     * Сохранить запись об образовании
     *
     * @param StoreUserEducationRequest $request
     *
     * @return BaseResource
     */
    public function store(StoreUserEducationRequest $request): BaseResource
    {
        $fileInfo = $request->hasFile('document')
            ? ['file' => $this->fileService->saveFile($request->file('document'))]
            : [];
        $newEducation = $request->user()->education()->create($request->validated() + $fileInfo);

        return new BaseResource([
            'message' => 'Education record stored successfully',
            'data' => [
                'id' => $newEducation->id,
            ],
        ]);
    }

    /**
     * Обновить запись об образовании
     *
     * @param UpdateUserEducationRequest $request
     * @param UserEducation              $education
     *
     * @return UserEducationResource
     */
    public function update(UpdateUserEducationRequest $request, UserEducation $education): UserEducationResource
    {
        $fileInfo = $request->hasFile('document')
            ? ['file' => $this->fileService->saveFile($request->file('document'))]
            : [];
        $education->update($request->validated() + $fileInfo);

        return new UserEducationResource($education);
    }

    /**
     * Удалить запись об образовании
     *
     * @param DestroyUserEducationRequest $request
     * @param UserEducation               $education
     *
     * @return Response
     */
    public function destroy(DestroyUserEducationRequest $request, UserEducation $education): Response
    {
        return response([
            'status' => (bool)$education->delete(),
            'message' => 'Success delete',
        ]);
    }
}
