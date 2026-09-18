<?php
// app/Mail/EmploymentApplicationReceived.php

namespace App\Mail;

use App\Models\EmploymentApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class EmploymentApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $isApplicantCopy;

    public function __construct(EmploymentApplication $application, $isApplicantCopy = false)
    {
        $this->application = $application;
        $this->isApplicantCopy = $isApplicantCopy;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->isApplicantCopy
                ? 'Employment Application Received - ' . config('app.name')
                : 'New Employment Application - ' . $this->application->full_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: $this->isApplicantCopy
                ? 'emails.employment.applicant-confirmation'
                : 'emails.employment.application-received',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
