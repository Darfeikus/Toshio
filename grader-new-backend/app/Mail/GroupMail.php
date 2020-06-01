<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class GroupMail extends Mailable
{
    public $name;

    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name)
    {
        $this->name = $name;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('onlinegraderitesm@gmail.com') // Sender mail
            ->subject('You have been assigned to a new group!') // Mail subject
            ->view('mail.group') // View file resource/views/mail/group
        ;
    }
}
