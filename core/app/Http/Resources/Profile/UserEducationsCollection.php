<?php

namespace App\Http\Resources\Profile;

use App\Http\Resources\BaseCollection;

class UserEducationsCollection extends BaseCollection
{
    public $collects = UserEducationResource::class;
}
