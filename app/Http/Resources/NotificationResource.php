<?php

namespace App\Http\Resources;

use App\Http\Traits\Notificatable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

use function App\Helpers\formatDate;

class NotificationResource extends JsonResource
{
    use Notificatable;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'      => $this->id,
            'title'   => $this->title,
            'body'    => $this->body,
            'item'    => $this->getNotificationItemResource($this->notificatable_type, $this->notificatable_id),
            'publish' => formatDate($this->created_at),
            'is_read' => $this->users->find(Auth::user()->id)->pivot->is_read
        ];
    }
}
