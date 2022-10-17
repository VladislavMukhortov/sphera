<?php

namespace App\Http\Resources;

class CommentsCollection extends BaseCollection
{
    public $collects = CommentResource::class;
}
