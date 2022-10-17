<?php

namespace App\Http\Controllers\Api\v1\Profile;

use App\Models\Profile\UserFamily;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\{
    DestroyUserFamilyRequest,
    StoreUserFamilyRequest,
    UpdateUserFamilyRequest
};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use App\Http\Resources\Profile\{
    UserFamilyResource, UserFamilyCollection
};

class UserFamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return UserFamilyCollection
     */
    public function index(): UserFamilyCollection
    {
        return new UserFamilyCollection(
            Auth::guard('sanctum')->user()->families->load('user')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserFamilyRequest $request
     *
     * @return UserFamilyResource
     */
    public function store(StoreUserFamilyRequest $request): UserFamilyResource
    {
        if ($request->has('relative_uuid')) {
            $request->merge(['relative_id' => User::firstWhere('uuid', $request->relative_uuid)->id]);
        }

        if ($request->hasFile('file')) {
            $image = Image::make($request->file->getRealPath())->encode('jpg');
            $imageName = Str::uuid() . '.jpg';
            Storage::disk('public')->put($imageName, $image->__toString());

            $request->merge(['photo' => Storage::disk('public')->url($imageName)]);
        }
        $family = $request->user()->families()->create($request->except('file'));

        return new UserFamilyResource($family->load('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserFamilyRequest $request
     * @param UserFamily              $family
     *
     * @return UserFamilyResource
     */
    public function update(UpdateUserFamilyRequest $request, UserFamily $family): UserFamilyResource
    {
        if ($request->has('relative_uuid')) {
            $request->merge(['relative_id' => User::firstWhere('uuid', $request->relative_uuid)->id]);
        }
        $family->update($request->except('relative_uuid'));

        return new UserFamilyResource($family->load('user'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyUserFamilyRequest $request
     * @param UserFamily               $family
     *
     * @return Response
     */
    public function destroy(DestroyUserFamilyRequest $request, UserFamily $family): Response
    {
        return response([
            'status' => (bool)$family->delete(),
            'message' => 'Success delete',
        ]);
    }
}
