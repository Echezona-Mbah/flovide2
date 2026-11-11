<?php

use App\Models\TransactionHistory;
use App\Traits\GeneratesTransactionReceipt;
use Barryvdh\DomPDF\Facade\Pdf;

uses(Tests\TestCase::class);

it('provides logo and QR assets for API receipt views', function () {
    $receipt = new class
    {
        use GeneratesTransactionReceipt;

        public function assets(TransactionHistory $transaction): array
        {
            return $this->receiptAssets($transaction);
        }
    };

    $assets = $receipt->assets(new TransactionHistory([
        'id' => 'transaction-123',
        'amount' => 100,
        'currency' => 'CAD',
        'status' => 'success',
        'reference' => 'reference-123',
    ]));

    expect(array_keys($assets))->toBe(['logo', 'qr'])
        ->and($assets['qr'])->toStartWith('data:image/svg+xml;base64,');
});

it('renders a transfer confirmation with receipt assets on a single page', function () {
    $data = [
        'title' => 'Transfer Confirmation',
        'date' => '09 Nov 2026',
        'status' => 'Completed',
        'amount' => '100.00 CAD',
        'transaction_id' => '019ff5f1-1301-73b4-800d-a0075fe046ff',
        'payment_reference' => 'a5426ac4-1e90-42df-9938-6788a400a913',
        'reference' => 'f3219b61-a506-4b6f-9f1e-123456789012',
        'bank_name' => 'Example International Commercial Bank',
        'account_name' => 'Example Recipient With A Longer Account Name',
        'account_number' => '01234567890123456789',
        'brand_name' => 'Flovide',
    ];

    $receipt = new class
    {
        use GeneratesTransactionReceipt;

        public function assets(TransactionHistory $transaction): array
        {
            return $this->receiptAssets($transaction);
        }
    };

    $data = array_merge($data, $receipt->assets(new TransactionHistory([
        'id' => $data['transaction_id'],
        'amount' => 100,
        'currency' => 'CAD',
        'status' => 'success',
        'reference' => $data['reference'],
        'created_at' => now(),
    ])));

    $html = view('pdf.transfer-confirmation', $data)->render();
    $pdf = Pdf::loadView('pdf.transfer-confirmation', $data);

    expect($html)->toContain('Flovide')
        ->and($html)->toContain('Scan for details')
        ->and($pdf->getDomPDF()->getCanvas()->get_page_count())->toBe(1);
});
