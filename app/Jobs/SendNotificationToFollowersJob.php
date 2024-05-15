<?php

namespace App\Jobs;

use App\Http\Traits\GetCommentItemTrait;
use App\Http\Traits\Notificatable;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationToFollowersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Notificatable, GetCommentItemTrait;

    public $model;
    public $title;
    public $body;

    /**
     * Create a new job instance.
     */
    public function __construct(Document|Comment $model, $title, $body)
    {
        $this->model = $model;
        $this->title = $title;
        $this->body  = $body;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $notification = $this->notificaionsStore($this->model, $this->title, $this->body);

        if ($this->model instanceof Comment) {
            $item = $this->getCommentItem($this->model->commentable_type, $this->model->commentable_id);
            if ($item instanceof Document) {
                $category = $item->category;
                $followers = $category->followers;
            } else if ($item instanceof Category) {
                $followers = $item->followers;
            }
        } else {
            $category  = $this->model->category;
            $followers = $category->followers;
        }

        if (!empty($followers)) {
            foreach ($followers as $follower) {
                $follower->user->notifications()->attach($notification->id);
            }
        }
        $this->sendNotification($notification);
    }
}
