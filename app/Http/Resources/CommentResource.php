<?php

namespace App\Http\Resources;

use App\Http\Traits\GetCommentItemTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use function App\Helpers\formatDate;

class CommentResource extends JsonResource
{
    use GetCommentItemTrait;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'user'        => $this->user->full_name,
            'description' => $this->description,
            'item'        => $this->getCommentItemResource($this->commentable_type, $this->commentable_id),
            'publish'     => formatDate($this->created_at)
        ];
    }
}
