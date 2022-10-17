<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Api\StoreUserFirebaseTokenRequest;
use App\Http\Resources\{UserFirebaseTokenCollection, UserFirebaseTokenResource};
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class FirebaseController extends Controller
{
    /**
     * Сохраняет или обновляет firebase-токен для авторизированного пользователя
     *
     * @param StoreUserFirebaseTokenRequest $request
     *
     * @return UserFirebaseTokenResource
     */
    public function store(StoreUserFirebaseTokenRequest $request): UserFirebaseTokenResource
    {
        $token = $request->user()->firebaseToken()->updateOrCreate([
            'user_id' => $request->user()->id
        ], [
            'token' => $request->token
        ]);

        return new UserFirebaseTokenResource($token);
    }

    /**
     * Возвращает firebase-токены авторизированного пользователя
     *
     * @return UserFirebaseTokenCollection
     */
    public function show(): UserFirebaseTokenCollection
    {
        return new UserFirebaseTokenCollection(
            Auth::guard('sanctum')->user()->firebaseTokens
        );
    }
}
