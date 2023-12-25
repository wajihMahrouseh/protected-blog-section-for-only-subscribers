<?php

namespace Modules\Blog\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'publishDate' => $this->publish_date,
            'status' => $this->status,
            'deletedAt' => $this->deleted_at,

            'image' => $this->whenLoaded('media', function () {
                return $this->getFirstMediaUrl('photos');
            }),
        ];
    }
}
