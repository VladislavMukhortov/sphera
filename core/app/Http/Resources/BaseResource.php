<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class BaseResource extends JsonResource
{
    public function with($request): array
    {
        $data = [
            'status' => true,
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
