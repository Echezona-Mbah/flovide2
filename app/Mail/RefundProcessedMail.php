<?php

namespace App\Mail;

use App\Models\TransactionHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RefundProcessedMail extends Mailable
{
    use Queueable, SerializesModels;

    public TransactionHistory $tx;
    public float $refundAmount;
    public float $newBalance;

    public function __construct(TransactionHistory $tx, float $refundAmount, float $newBalance)
    {
        $this->tx = $tx;
        $this->refundAmount = $refundAmount;
        $this->newBalance = $newBalance;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Refund Processed - ' . ($this->tx->reference ?? 'Transaction')
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.refund_processed',
            with: [
                'tx' => $this->tx,
                'refundAmount' => $this->refundAmount,
                'newBalance' => $this->newBalance,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
