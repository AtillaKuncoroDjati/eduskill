<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;
    public $name;

    /**
     * Create a new message instance.
     */
    public function __construct($token, $email, $name)
    {
        $this->token = $token;
        $this->email = $email;
        $this->name = $name;
    }

    public function build()
    {
        return $this->subject('Reset Kata Sandi')
            ->view('emails.password_reset')
            ->with([
                'token' => $this->token,
                'email' => $this->email,
                'name' => $this->name,
            ]);
    }
}
