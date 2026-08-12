<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminNewRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $account;
    public $type; // 'business' or 'personal'

    public function __construct($account, string $type)
    {
        $this->account = $account;
        $this->type = $type;
    }

    public function build()
    {
        return $this->subject('New ' . ucfirst($this->type) . ' Registration - Flovide')
            ->view('email.admin-new-registration');
    }
}