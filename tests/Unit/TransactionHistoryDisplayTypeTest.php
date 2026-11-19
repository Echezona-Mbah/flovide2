<?php

namespace Tests\Unit;

use App\Models\TransactionHistory;
use PHPUnit\Framework\TestCase;

class TransactionHistoryDisplayTypeTest extends TestCase
{
    public function test_it_labels_api_payouts_and_collections_from_transaction_fields(): void
    {
        $payout = new TransactionHistory([
            'method' => 'withdrawal',
            'api_source' => true,
        ]);
        $collection = new TransactionHistory([
            'type' => 'credit',
            'method' => 'credit',
            'transaction_type' => 'payment',
            'api_source' => true,
        ]);

        $this->assertSame('Payout', $payout->display_type);
        $this->assertSame('Collection', $collection->display_type);
    }

    public function test_it_preserves_swap_and_normal_transaction_labels(): void
    {
        $swap = new TransactionHistory(['type' => 'Swap']);
        $credit = new TransactionHistory(['type' => 'credit']);

        $this->assertSame('Swap', $swap->display_type);
        $this->assertSame('Credit', $credit->display_type);
    }
}
