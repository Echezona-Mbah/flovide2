<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        /*
         * @page margin: top right bottom left
         * We reserve 90px at the bottom for the fixed footer so body content
         * never overlaps it and DomPDF never creates an extra page.
         */
        @page {
            margin: 48px 60px 90px 60px;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #111827;
            font-size: 13px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        /* ── Header (table layout — DomPDF handles tables better than floats) ── */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 28px;
        }
        .header-table td {
            vertical-align: top;
            padding: 0;
        }
        .header-left  { width: 50%; }
        .header-right { width: 50%; text-align: right; }

        .logo  { width: 160px; height: auto; display: block; }
        .wordmark { font-size: 28px; font-weight: 700; color: #215F9C; line-height: 1; }

        h1 { margin: 0 0 6px; font-size: 22px; line-height: 1.2; color: #000; }
        .date-line { font-size: 12px; color: #555; margin-bottom: 6px; }
        .status-badge {
            display: inline-block;
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 5px 12px;
            font-size: 12px;
            font-weight: 700;
            color: #111;
        }

        /* ── Divider ── */
        .divider {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 0 0 18px 0;
        }

        /* ── Amount hero ── */
        .amount-block {
            margin-bottom: 20px;
        }
        .amount-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #6b7280;
            margin-bottom: 2px;
        }
        .amount-value {
            font-size: 28px;
            font-weight: 700;
            color: #111;
        }

        /* ── Detail rows ── */
        .details {
            width: 100%;
            border-collapse: collapse;
        }
        .details tr {
            border-bottom: 1px solid #f3f4f6;
        }
        .details td {
            padding: 7px 0;
            vertical-align: top;
            font-size: 12.5px;
        }
        .label { width: 36%; font-weight: 700; color: #374151; }
        .value { width: 64%; color: #111; word-break: break-all; }

        /* ── Section title ── */
        .section-title {
            margin: 22px 0 10px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #374151;
        }

        /* Fixed footer — coordinates are relative to the full page canvas,
              not the @page margin box, so we place it at bottom: 0 within
              a container that fills the reserved 90px bottom margin.       */
        .footer {
            position: fixed;
            left:   0;
            right:  0;
            bottom: 0;
            height: 82px;
            padding: 10px 60px 0;
            border-top: 1px solid #e5e7eb;
            background: #fff;
        }
        .footer-inner {
            width: 100%;
            border-collapse: collapse;
        }
        .footer-inner td { vertical-align: middle; padding: 0; }
        .footer-qr-cell  { width: 72px; }
        .footer-text-cell { padding-left: 12px; }

        .qr  { width: 62px; height: 62px; display: block; }
        .qr-caption { font-size: 7px; color: #9ca3af; text-align: center; margin-top: 2px; }

        .footer-text {
            font-size: 8.5px;
            color: #6b7280;
            line-height: 1.5;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <table class="header-table">
        <tr>
            <td class="header-left">
                @if(!empty($logo))
                    <img src="{{ $logo }}" alt="{{ $brand_name }}" class="logo">
                @else
                    <div class="wordmark">{{ $brand_name }}</div>
                @endif
            </td>
            <td class="header-right">
                <h1>{{ $title }}</h1>
                <div class="date-line">{{ $date }}</div>
                <span class="status-badge">{{ $status }}</span>
            </td>
        </tr>
    </table>

    <hr class="divider">

    {{-- Amount hero --}}
    <div class="amount-block">
        <div class="amount-label">Total Amount</div>
        <div class="amount-value">{{ $amount }}</div>
    </div>

    {{-- ── Transaction details ── --}}
    <div class="section-title">Transaction Details</div>
    <table class="details">
        <tr>
            <td class="label">Transaction ID</td>
            <td class="value">{{ $transaction_id }}</td>
        </tr>
        <tr>
            <td class="label">Payment Reference</td>
            <td class="value">{{ $payment_reference }}</td>
        </tr>
        <tr>
            <td class="label">Reference</td>
            <td class="value">{{ $reference }}</td>
        </tr>
        <tr>
            <td class="label">Date</td>
            <td class="value">{{ $date }}</td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td class="value">{{ $status }}</td>
        </tr>
    </table>

    {{-- Recipient details (only when present) --}}
    @if(!empty($account_number))
        <div class="section-title">Recipient Details</div>
        <table class="details">
            <tr>
                <td class="label">Bank Name</td>
                <td class="value">{{ $bank_name }}</td>
            </tr>
            <tr>
                <td class="label">Account Name</td>
                <td class="value">{{ $account_name }}</td>
            </tr>
            <tr>
                <td class="label">Account Number</td>
                <td class="value">{{ $account_number }}</td>
            </tr>
        </table>
    @endif

    {{-- ── Fixed footer ── --}}
    <div class="footer">
        <table class="footer-inner">
            <tr>
                @if(!empty($qr))
                <td class="footer-qr-cell">
                    <img src="{{ $qr }}" class="qr" alt="QR Code">
                    <div class="qr-caption">Scan for details</div>
                </td>
                @endif
                <td class="footer-text-cell">
                    <div class="footer-text">
                        {{ $brand_name }} is registered as a money service business with the Financial Transactions
                        and Reports Analysis Centre of Canada (FINTRAC).<br>
                        {{ $brand_name }} is registered as a money service business with the United States Financial
                        Crimes Enforcement Network (FinCEN).
                    </div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>