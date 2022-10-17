<?php

namespace App\Http\Resources;

class OffersCollection extends BaseCollection
{
    public $collects = OfferResource::class;

    /**
     * Подсчет общего количества заявок
     *
     * @param $request
     *
     * @return array
     */
    public function with($request): array
    {
        return [
            'count' => $this->count(),
        ];
    }
}
