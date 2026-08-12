<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1A1D2E; }
        .header { text-align: center; margin-bottom: 22px; }
        .header h1 { font-size: 18px; margin: 0 0 4px 0; }
        .header p { margin: 2px 0; color: #666; }

        table.summary { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.summary td {
            padding: 10px;
            border: 1px solid #E5E7EB;
            width: 25%;
            text-align: center;
        }
        table.summary .label { display: block; font-size: 9px; color: #888; text-transform: uppercase; margin-bottom: 3px; }
        table.summary .value { font-size: 12px; font-weight: bold; }

        table.transactions { width: 100%; border-collapse: collapse; }
        table.transactions th, table.transactions td {
            border: 1px solid #E5E7EB;
            padding: 6px 8px;
            font-size: 9.5px;
        }
        table.transactions th { background: #1A1D2E; color: #fff; text-align: left; }
        .credit { color: #059669; font-weight: bold; }
        .debit  { color: #DC2626; font-weight: bold; }

        .footer { margin-top: 25px; text-align: center; font-size: 8.5px; color: #999; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Flovide Account Statement</h1>
        <p>{{ $balance->currency }} Wallet &middot; {{ $balance->name }}</p>
        <p>{{ $startDate->format('M j, Y') }} &ndash; {{ $endDate->format('M j, Y') }}</p>
    </div>

    <table class="summary">
        <tr>
            <td>
                <span class="label">Opening Balance</span>
                <span class="value">{{ number_format($openingBalance, 2) }} {{ $balance->currency }}</span>
            </td>
            <td>
                <span class="label">Total Credit</span>
                <span class="value credit">+{{ number_format($totalCredit, 2) }} {{ $balance->currency }}</span>
            </td>
            <td>
                <span class="label">Total Debit</span>
                <span class="value debit">-{{ number_format($totalDebit, 2) }} {{ $balance->currency }}</span>
            </td>
            <td>
                <span class="label">Closing Balance</span>
                <span class="value">{{ number_format($closingBalance, 2) }} {{ $balance->currency }}</span>
            </td>
        </tr>
    </table>

    <table class="transactions">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Reference</th>
                <th>Status</th>
                <th style="text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $tx)
                @php
                    $type = strtolower($tx->type ?? '');
                    $txIsCredit = in_array($type, ['credit']) || str_contains($type, 'credit');
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($tx->created_at)->format('M j, Y g:i A') }}</td>
                    <td>{{ ucfirst($tx->type ?? 'N/A') }}</td>
                    <td>{{ $tx->reference ?? 'N/A' }}</td>
                    <td>{{ ucfirst($tx->status ?? 'N/A') }}</td>
                    <td class="{{ $txIsCredit ? 'credit' : 'debit' }}" style="text-align: right;">
                        {{ $txIsCredit ? '+' : '-' }}{{ number_format($tx->amount, 2) }} {{ $tx->currency }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">No transactions in this period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Flovide Financial Services &middot; support@flovide.com<br>
        Generated on {{ now()->format('M j, Y g:i A') }}
    </div>

</body>
</html>