<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page { margin: 35px 45px; }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #111827;
            font-size: 13px;
            line-height: 1.4;
        }

        .header { width: 100%; margin-bottom: 34px; }
        .header td { vertical-align: top; }
        .brand { width: 50%; }
        .logo { width: 190px; height: auto; display: block; }
        .wordmark { font-size: 30px; font-weight: 700; color: #215F9C; }

        .header-right { width: 50%; text-align: right; }
        h1 { margin: 0 0 8px; font-size: 26px; line-height: 1.2; color: #000; }
        .date { font-size: 13px; margin-bottom: 8px; }
        .status { background: #f3f4f6; padding: 10px 12px; text-align: left; font-size: 14px; }

        .details { width: 100%; border-collapse: collapse; margin-top: 8px; page-break-inside: avoid; }
        .details td { padding: 10px 8px; vertical-align: top; border-bottom: 1px solid #e5e7eb; }
        .label { width: 32%; font-weight: 700; color: #4b5563; }
        .value { width: 68%; color: #111827; word-break: break-all; }
        .amount-row td { background: #f3f7fb; }
        .amount-value { color: #215F9C; font-size: 20px; font-weight: 700; }

        .section-title { margin-top: 30px; margin-bottom: 8px; font-size: 19px; font-weight: 700; color: #000; }

        .footer {
            margin-top: 54px;
            color: #666;
            font-size: 9px;
            line-height: 1.4;
        }
        .footer-table { width: 100%; border-collapse: collapse; }
        .qr-cell { width: 96px; padding-right: 14px; text-align: center; vertical-align: top; }
        .qr { width: 82px; height: 82px; }
        .qr-caption { font-size: 8px; color: #666; margin-top: 3px; }
        .footer-text { vertical-align: middle; }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td class="brand">
                @if(!empty($logo))
                    <img src="{{ $logo }}" alt="{{ $brand_name }}" class="logo">
                @else
                    <div class="wordmark">{{ $brand_name }}</div>
                @endif
            </td>
            <td class="header-right">
                <h1>{{ $title }}</h1>
                <div class="date">{{ $date }}</div>
                <div class="status">Status: {{ $status }}</div>
            </td>
        </tr>
    </table>

    <table class="details">
        <tr class="amount-row"><td class="label">Amount</td><td class="value amount-value">{{ $amount }}</td></tr>
        <tr><td class="label">Transaction ID</td><td class="value">{{ $transaction_id }}</td></tr>
        <tr><td class="label">Payment reference</td><td class="value">{{ $payment_reference }}</td></tr>
        <tr><td class="label">Reference</td><td class="value">{{ $reference }}</td></tr>
    </table>

    @if($account_number)
        <div class="section-title">Recipient details</div>

        <table class="details">
            <tr><td class="label">Bank name</td><td class="value">{{ $bank_name }}</td></tr>
            <tr><td class="label">Account name</td><td class="value">{{ $account_name }}</td></tr>
            <tr><td class="label">Account number</td><td class="value">{{ $account_number }}</td></tr>
        </table>
    @endif

    <table class="footer footer-table">
        <tr>
            @if(!empty($qr))
                <td class="qr-cell">
                    <img src="{{ $qr }}" class="qr" alt="QR">
                    <div class="qr-caption">Scan for details</div>
                </td>
            @endif
            <td class="footer-text">
                {{ $brand_name }} is registered as a money service business with the Financial Transactions and Reports Analysis Centre of Canada (FINTRAC).
                <br>
                {{ $brand_name }} is registered as a money service business with the United States Financial Crimes Enforcement Network (FinCEN).
            </td>
        </tr>
    </table>
</body>
</html>