<?php

namespace App\Observers;

use App\Jobs\SendEmailToAllUserJob;
use App\Jobs\SendNotificationToAllJob;
use App\Models\category;

class CategoryObserver
{
    /**
     * Handle the category "created" event.
     */
    public function created(category $category): void
    {
        $subject = 'Add a new category';
        $message = 'The category “'. $category->name .'” has been added. You can follow it if you are interested';

        SendEmailToAllUserJob::dispatch($subject, $message);
        SendNotificationToAllJob::dispatch($category, $subject, $message);
    }
}
