<?php

namespace Modules\Subscriber\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscribersListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'name' => $this->user->name,
            'username' => $this->user->username,
            'status' => $this->status,
            'deletedAt' => $this->deleted_at
        ];
    }
}
