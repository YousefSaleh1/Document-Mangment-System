<?php

namespace App\Jobs;

use App\Mail\SendMail;
use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailToFollowersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $document;
    public $subject;
    public $content;

    /**
     * Create a new job instance.
     */
    public function __construct(Document $document, string $subject, string $content)
    {
        $this->document = $document;
        $this->subject  = $subject;
        $this->content  = $content;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $category  = $this->document->category;
        $followers = $category->followers;
        foreach ($followers as $follower) {
            Mail::to($follower->user->email)->queue(new SendMail($this->document->user->full_name, $this->subject, $this->content));
        }
    }
}
