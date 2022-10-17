<?php

namespace App\Http\Resources\Profile;

use App\Http\Resources\BaseCollection;

class UserCareersCollection extends BaseCollection
{
    public $collects = UserCareerResource::class;
}
