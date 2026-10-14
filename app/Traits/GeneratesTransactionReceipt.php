<?php

namespace App\Traits;

use App\Models\TransactionHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

trait GeneratesTransactionReceipt
{
    protected function receiptResponse(TransactionHistory $tx)
    {
        $type = strtolower($tx->type ?? '');

        $title = match (true) {
            $type === 'credit'          => 'Deposit Confirmation',
            str_contains($type, 'swap') => 'Swap Confirmation',
            default                     => 'Transfer Confirmation',
        };

        $status = match ($tx->status) {
            'success' => 'Completed',
            'failed'  => 'Failed',
            default   => 'Pending',
        };

        $number = number_format((float) $tx->amount, 2);
        $symbol = $tx->currency_symbol ?? '';
        $amount = $symbol ? $symbol . $number : $number . ' ' . strtoupper((string) $tx->currency);

        $date          = optional($tx->created_at)->format('d M Y');
        $transactionId = $tx->order_id ?: (string) $tx->id;

        // What you see when you scan the QR code (no symbols, keeps the QR small and clean)
        $qrText = implode("\n", array_filter([
            'FLOVIDE RECEIPT',
            "Status: {$status}",
            "Amount: {$number} " . strtoupper((string) $tx->currency),
            "Date: {$date}",
            "Ref: {$tx->reference}",
            "Txn ID: {$transactionId}",
            $tx->recipient_account_name ? "To: {$tx->recipient_account_name}" : null,
        ]));

        $qrSvg = (string) QrCode::format('svg')
            ->size(200)
            ->margin(0)
            ->errorCorrection('M')
            ->encoding('UTF-8')
            ->generate($qrText);

        $logoPath = public_path('asserts/homepage/Logo.png');   // adjust if your logo lives elsewhere

        $data = [
            'brand_name'        => 'Flovide',
            'title'             => $title,
            'date'              => $date,
            'status'            => $status,
            'amount'            => $amount,
            'transaction_id'    => $transactionId,
            'payment_reference' => $tx->payment_reference ?: '—',
            'reference'         => $tx->reference ?: '—',
            'bank_name'         => $tx->recipient_bank_name ?: '—',
            'account_name'      => $tx->recipient_account_name ?: '—',
            'account_number'    => $tx->recipient_account_number ?: null,
            'logo'              => is_file($logoPath)
                ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
                : null,
            'qr'                => 'data:image/svg+xml;base64,' . base64_encode($qrSvg),
        ];

        $filename = 'Flovide-Receipt-' . preg_replace('/[^A-Za-z0-9\-_]/', '', (string) $tx->reference) . '.pdf';

        return Pdf::loadView('pdf.transfer-confirmation', $data)
            ->setPaper('a4')
            ->download($filename);
    }
}