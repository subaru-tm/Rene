<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $content;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $subject, string $content)
    {
        $this->onQueue('emails');
        $this->subject = $subject;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
            ->view('mail.notify')
            ->with([
                'content' => $this->content,
            ]);
    }
}
