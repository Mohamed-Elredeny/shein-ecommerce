<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DemoEmailApiForegtPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The demo object instance.
     *
     */
    public $demo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($demo)
    {
        $this->demo = $demo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_USERNAME'),'Coins Cozmatices')
            ->subject('Forget Password')
            ->view('mail.demo-forget-pass-api');
    }
}
