<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminPushNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $business_name;
    public $subjectTitle;
    public $message;

    /**
     * Create a new message instance.
     */
    public function __construct(string $business_name, string $subjectTitle, string $message)
    {
        $this->business_name = $business_name;
        $this->subjectTitle = $subjectTitle;
        $this->message = $message;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Flovide Notification',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'email.adminpushnotification',
            with: [
                'business_name' => $this->business_name,
                'subjectTitle' => $this->subjectTitle,
                'messageBody' => $this->message,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
