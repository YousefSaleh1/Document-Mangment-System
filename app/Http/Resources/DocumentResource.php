<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

use function App\Helpers\formatDate;

class DocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'user'                 => $this->user->full_name,
            'title'                => $this->title,
            'description'          => $this->description,
            'file'                 => asset(Storage::url($this->file)),
            'downloads_count'      => $this->downloadsCount(),
            'visitor_count'        => $this->visitorCount(),
            'timeSincePublication' => formatDate($this->created_at),
            'tags'                 => TagResource::collection($this->tags)
        ];
    }
}
