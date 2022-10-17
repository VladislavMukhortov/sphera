<?php

namespace App\Http\Resources\Profile;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserEducationResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'          => (int)$this->id,
            'university'  => $this->university,
            'speciality'  => $this->speciality,
            'file'        => $this->when($this->file, Storage::disk('public')->url($this->file), null),
            'date_start'  => $this->date_start->format('d-m-Y'),
            'date_end'    => $this->date_end?->format('d-m-Y'),
        ];
    }
}
