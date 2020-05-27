<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TestMail extends Mailable
{
    public $username;
    public $password;

    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$pass)
    {
        $this->username = $user;
        $this->password = $pass;
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
            ->subject('Your new password for grading platform') // Mail subject
            ->view('mail.index') // View file resource/views/mail/index
        ;
    }
}
