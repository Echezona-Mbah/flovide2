<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page { margin: 55px 65px; }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #111827;
            font-size: 14px;
            line-height: 1.45;
        }

        .header { width: 100%; margin-bottom: 70px; }

        .brand { float: left; width: 45%; }
        .logo { width: 220px; height: auto; display: block; }
        .wordmark { font-size: 34px; font-weight: 700; color: #215F9C; }

        .header-right { float: right; width: 50%; text-align: right; }
        h1 { margin: 0 0 8px; font-size: 30px; line-height: 1.2; color: #000; }
        .date { font-size: 14px; margin-bottom: 8px; }
        .status { background: #f3f4f6; padding: 12px 14px; text-align: left; font-size: 16px; }

        .clear { clear: both; }

        .details { width: 100%; border-collapse: collapse; margin-top: 6px; }
        .details td { padding: 9px 0; vertical-align: top; }
        .label { width: 32%; font-weight: 700; color: #000; }
        .value { width: 68%; color: #000; word-break: break-all; }

        .section-title { margin-top: 48px; margin-bottom: 14px; font-size: 21px; font-weight: 700; color: #000; }

        .footer {
            position: fixed;
            left: 0; right: 0; bottom: 0;
            color: #666;
            font-size: 10.5px;
            line-height: 1.45;
        }
        .qr-wrap { float: left; width: 96px; margin-right: 16px; text-align: center; }
        .qr { width: 96px; height: 96px; display: block; }
        .qr-caption { font-size: 8px; color: #666; margin-top: 3px; }
        .footer-text { overflow: hidden; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">
            @if($logo)
                <img src="{{ $logo }}" alt="{{ $brand_name }}" class="logo">
            @else
                <div class="wordmark">{{ $brand_name }}</div>
            @endif
        </div>

        <div class="header-right">
            <h1>{{ $title }}</h1>
            <div class="date">{{ $date }}</div>
            <div class="status">Status: {{ $status }}</div>
        </div>
    </div>

    <div class="clear"></div>

    <table class="details">
        <tr><td class="label">Amount</td><td class="value">{{ $amount }}</td></tr>
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

    <div class="footer">
        <div class="qr-wrap">
            <img src="{{ $qr }}" class="qr" alt="QR">
            <div class="qr-caption">Scan for details</div>
        </div>
        <div class="footer-text">
            {{ $brand_name }} is registered as a money service business with the Financial Transactions and Reports Analysis Centre of Canada (FINTRAC).
            <br>
            {{ $brand_name }} is registered as a money service business with the United States Financial Crimes Enforcement Network (FinCEN).
            <br>
              {{-- {{ $brand_name }} is duly registered in Canada as a Money Services Business (MSB) with FINTRAC under registration number C100000869.
            <br>
              In the United States, Flovide is registered as a Money Services Business with FinCEN under registration number 31000301191487.
            <br> --}}

        </div>
    </div>
</body>
</html>