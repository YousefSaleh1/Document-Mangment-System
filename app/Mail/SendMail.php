<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $subject;
    public $content;

    /**
     * Create a new message instance.
     */
    public function __construct(string $user, string $subject, string $content)
    {
        $this->user     = $user;
        $this->subject  = $subject;
        $this->content  = $content;
    }

    /**
     * Build the email message for sending to followers.
     *
     * This method constructs the email message for sending to the followers. It sets the subject, the view,
     * and the data that will be passed to the view.
     *
     * @return Illuminate\Mail\Mailable The instance of the email message.
     */
    public function build()
    {
        return $this->subject($this->subject)
            ->view('emails.send_mail_to_folowers')
            ->with([
                'user'     => $this->user,
                'content'  => $this->content
            ]);
    }
}
