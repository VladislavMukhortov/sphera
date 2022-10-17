<?php

namespace App\Http\Controllers\Api\v1\Profile;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Profile\UserCareer;
use App\Http\Requests\Api\Profile\{
    DestroyUserCareerRequest,
    StoreUserCareerRequest,
    UpdateUserCareerRequest
};
use App\Http\Resources\Profile\{
    UserCareersCollection,
    UserCareerResource
};

class UserCareerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return UserCareersCollection
     */
    public function index(): UserCareersCollection
    {
        return new UserCareersCollection(
            Auth::guard('sanctum')->user()->career
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserCareerRequest $request
     *
     * @return UserCareerResource
     */
    public function store(StoreUserCareerRequest $request): UserCareerResource
    {
        return new UserCareerResource(
            Auth::guard('sanctum')->user()
                ->career()
                ->create($request->validated())
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserCareerRequest $request
     * @param UserCareer              $career
     *
     * @return UserCareerResource
     */
    public function update(UpdateUserCareerRequest $request, UserCareer $career): UserCareerResource
    {
        $career->update($request->validated());

        return new UserCareerResource($career);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyUserCareerRequest $request
     * @param UserCareer               $career
     *
     * @return Response
     */
    public function destroy(DestroyUserCareerRequest $request, UserCareer $career): Response
    {
        return response([
            'status' => (bool)$career->delete(),
            'message' => 'Success delete',
        ]);
    }
}
