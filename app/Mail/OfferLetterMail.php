<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\StaffDocument;
use App\Models\StaffProfile;

class OfferLetterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $document;
    public $staff;
    public $emailMessage;

    /**
     * Create a new message instance.
     */
    public function __construct(StaffDocument $document, StaffProfile $staff, $emailMessage = null)
    {
        $this->document = $document;
        $this->staff = $staff;
        $this->emailMessage = $emailMessage;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Offer Letter Notification - ' . $this->document->title)
                    ->view('emails.offer-letter-mail')
                    ->with([
                        'document' => $this->document,
                        'staff' => $this->staff,
                        'emailMessage' => $this->emailMessage,
                    ]);
    }
}