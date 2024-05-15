<?php

namespace App\Observers;

use App\Http\Traits\GetCommentItemTrait;
use App\Jobs\SendNotificationToFollowersJob;
use App\Models\Category;
use App\Models\Comment;

class CommentObserver
{
    use GetCommentItemTrait;

    /**
     * Handle the Comment "created" event.
     */
    public function created(Comment $comment): void
    {
        $item = $this->getCommentItem($comment->commentable_type, $comment->commentable_id);
        $subject = 'New comment';
        if ($item instanceof Category) {
            $body = 'A new comment has been added in the category followed by "' . $item->name . '" by "' . $comment->user->full_name . '".';
        } else {
            $body = 'A new comment has been added in the category followed by "' . $item->category->name . '" in Document "' . $item->title . '" by "' . $comment->user->full_name . '".';
        }
        SendNotificationToFollowersJob::dispatch($comment, $subject, $body);
    }

    /**
     * Handle the Comment "updated" event.
     */
    public function updated(Comment $comment): void
    {
        $item = $this->getCommentItem($comment->commentable_type, $comment->commentable_id);
        $subject = 'Update comment';
        if ($item instanceof Category) {
            $body = 'A comment has been updated in the category followed by ' . $item->name . '" by "' . $comment->user->full_name . '".';
        } else {
            $body = 'A comment has been updated in the category followed by ' . $item->category->name . '" in Document "' . $item->title . '" by "' . $comment->user->full_name . '".';
        }
        SendNotificationToFollowersJob::dispatch($comment, $subject, $body);
    }
}
