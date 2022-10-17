<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;

class BaseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $data = [
            'status' => true,
            'data' => $this->collection,
        ];

        if (config('app.debug')) {
            $data += [
                'request' => $request->all(),
                'debug' => DB::getQueryLog()
            ];
        }

        return $data;
    }
}
