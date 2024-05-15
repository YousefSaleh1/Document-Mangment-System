<?php

namespace App\Observers;

use App\Jobs\SendEmailToFollowersJob;
use App\Jobs\SendNotificationToFollowersJob;
use App\Models\Document;

class DocumentObserver
{
    /**
     * Handle the Document "created" event.
     */
    public function created(Document $document): void
    {
        $subject = 'Add a new document';
        $message = 'A new document has been added to the category "' . $document->category->name . '"  by :' . $document->user->full_name . '.';
        SendEmailToFollowersJob::dispatch($document, $subject, $message);
        SendNotificationToFollowersJob::dispatch($document, $subject, $message);
    }

    /**
     * Handle the Document "updated" event.
     */
    public function updated(Document $document): void
    {
        $subject = 'Update a document';
        $message = 'The document "'. $document->title .'" for the category "'. $document->category->name .'" has been updated.';
        SendEmailToFollowersJob::dispatch($document, $subject, $message);
        SendNotificationToFollowersJob::dispatch($document, $subject, $message);
    }
}
