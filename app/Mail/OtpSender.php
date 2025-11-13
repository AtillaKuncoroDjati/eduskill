<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpSender extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $nama;
    public $otpCode;

    /**
     * Create a new message instance.
     */
    public function __construct($email, $nama, $otpCode)
    {
        $this->email = $email;
        $this->nama = $nama;
        $this->otpCode = $otpCode;
    }

    public function build()
    {
        return $this->subject('Kode Verifikasi')->view('emails.otp')->with([
            'email' => $this->email,
            'nama' => $this->nama,
            'otpCode' => $this->otpCode,
        ]);
    }
}
