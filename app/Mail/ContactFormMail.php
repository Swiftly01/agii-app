<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $isConfirmation;

    /**
     * Create a new message instance.
     */
    public function __construct($data, $isConfirmation = false)
    {
        $this->data = $data;
        $this->isConfirmation = $isConfirmation;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        if ($this->isConfirmation) {
            // Email to the user
            return $this->subject('Thank you for contacting AGII')
                        ->view('emails.contact-confirmation')
                        ->with(['data' => $this->data]);
        } else {
            // Email to your team
            return $this->subject('New Contact Form Submission: ' . ($this->data['subject'] ?? 'No Subject'))
                        ->view('emails.contact-notification')
                        ->with(['data' => $this->data]);
        }
    }
}