<?php

namespace App\Http\Resources;

class StudentsCollection extends BaseCollection
{
    public $collects = FollowResource::class;

    /**
     * Добавить счетчик учеников в их коллекцию
     *
     * @param $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'count' => $this->collection->count(),
            'data' => $this->collection
        ];
    }
}
