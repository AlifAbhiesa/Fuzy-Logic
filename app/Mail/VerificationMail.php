<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationMail extends Mailable
{
    use Queueable, SerializesModels;
    public $token;
    public $act;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token,$act)
    {
        //
        $this->token = $token;
        $this->act = $act;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('marketing@warung.com')
                   ->view('verificationmailview');
    }
}
