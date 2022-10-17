<?php

namespace App\Http\Resources;

class UsersCollection extends BaseCollection
{
    public $collects = UserResource::class;
}
