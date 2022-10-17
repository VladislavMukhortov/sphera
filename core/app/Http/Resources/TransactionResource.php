<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class TransactionResource extends BaseResource
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
            'type'          => $this->namedType,
            'amount'        => (int)$this->signedAmount,
            'sender'        => UserResource::make($this->sender),
            'recipient'     => UserResource::make($this->recipient),
            'created_at'    => $this->created_at->format('d-m-Y'),
        ];
    }
}
