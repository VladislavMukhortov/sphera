<?php

namespace App\Http\Resources;

class PostsCollection extends BaseCollection
{
    public $collects = PostResource::class;
}
