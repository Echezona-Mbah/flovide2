<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeamInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $owner;
    public $token;

    public function __construct($owner, $token)
    {
        $this->owner = $owner;
        $this->token = $token;
    }

    public function build()
    {
        $url = url('/team/invite/' . $this->token);

        return $this->subject('You have been invited to join a team')
            ->view('email.team-invite')
            ->with([
                'ownerName' => $this->owner->name,
                'url' => $url,
            ]);
    }
}
