<?php

namespace App\Jobs;

use App\Http\Traits\Notificatable;
use App\Models\Category;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationToAllJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Notificatable;

    public $category;
    public $title;
    public $body;

    /**
     * Create a new job instance.
     */
    public function __construct(Category $category, string $title, string $body)
    {
        $this->category = $category;
        $this->title    = $title;
        $this->body     = $body;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $notification = $this->notificaionsStore($this->category, $this->title, $this->body);
        $users = User::all();

        foreach ($users as $user) {
            $user->notifications()->attach($notification->id);
        }
        $this->sendNotification($notification);
    }
}
