<?php

namespace App\Http\Resources\Profile;

use App\Http\Resources\BaseCollection;

class SignInsCollection extends BaseCollection
{
    public $collects = SignInResource::class;
}
