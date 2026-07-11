<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Statement · {{ $balance->currency }} · {{ $startDate->format('M Y') }}</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'Inter',sans-serif; background:#f0f2f7; color:#1A1D2E; }
    .page { max-width:900px; margin:0 auto; background:#fff; }

    /* Header */
    .stmt-header { background:#1A1D2E; color:#fff; padding:40px 48px 32px; }
    .stmt-header .logo { display:flex; align-items:center; gap:12px; margin-bottom:32px; }
    .stmt-header .logo-name { font-size:22px; font-weight:800; letter-spacing:-0.5px; }
    .stmt-header .meta { display:flex; justify-content:space-between; flex-wrap:wrap; gap:16px; }
    .stmt-header .meta-left h1 { font-size:26px; font-weight:800; letter-spacing:-0.5px; }
    .stmt-header .meta-left p { color:rgba(255,255,255,0.5); font-size:13px; margin-top:4px; }
    .stmt-header .meta-right { text-align:right; }
    .stmt-header .meta-right p { color:rgba(255,255,255,0.5); font-size:12px; }
    .stmt-header .meta-right strong { font-size:14px; font-weight:600; color:#fff; }

    /* Account info */
    .account-bar { background:#2D3561; padding:20px 48px; display:flex; justify-content:space-between; flex-wrap:wrap; gap:12px; }
    .account-bar .item { }
    .account-bar .item label { font-size:11px; color:rgba(255,255,255,0.45); text-transform:uppercase; letter-spacing:.08em; display:block; margin-bottom:3px; }
    .account-bar .item span { font-size:14px; font-weight:600; color:#fff; }

    /* Summary */
    .summary { padding:32px 48px; border-bottom:1px solid #F0F2F7; }
    .summary h2 { font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:#6B7280; margin-bottom:16px; }
    .summary-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:1px; background:#F0F2F7; border-radius:16px; overflow:hidden; }
    .summary-cell { background:#fff; padding:20px; }
    .summary-cell label { font-size:11px; color:#9CA3AF; text-transform:uppercase; letter-spacing:.06em; display:block; margin-bottom:6px; }
    .summary-cell .val { font-size:18px; font-weight:800; color:#1A1D2E; }
    .summary-cell .val.credit { color:#059669; }
    .summary-cell .val.debit  { color:#DC2626; }
    .summary-cell .note { font-size:10px; color:#D1D5DB; margin-top:4px; }

    /* Transactions */
    .tx-section { padding:32px 48px 48px; }
    .tx-section h2 { font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:#6B7280; margin-bottom:20px; }
    table { width:100%; border-collapse:collapse; }
    thead th { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#9CA3AF; padding:10px 14px; text-align:left; background:#F9FAFB; border-bottom:2px solid #F0F2F7; }
    thead th.right { text-align:right; }
    tbody tr { border-bottom:1px solid #F9FAFB; }
    tbody tr:hover { background:#FAFBFF; }
    tbody td { padding:14px 14px; font-size:13px; color:#374151; vertical-align:middle; }
    tbody td.right { text-align:right; font-weight:700; }
    tbody td .ref { font-size:11px; color:#9CA3AF; margin-top:2px; }
    .badge { display:inline-block; padding:3px 8px; border-radius:999px; font-size:10px; font-weight:700; text-transform:uppercase; }
    .badge-credit   { background:#D1FAE5; color:#065F46; }
    .badge-debit    { background:#FEE2E2; color:#991B1B; }
    .badge-pending  { background:#FEF9C3; color:#854D0E; }
    .badge-success  { background:#D1FAE5; color:#065F46; }
    .badge-failed   { background:#FEE2E2; color:#991B1B; }
    .amount-credit  { color:#059669; }
    .amount-debit   { color:#DC2626; }

    /* Empty */
    .empty { text-align:center; padding:60px 20px; color:#9CA3AF; }
    .empty i { font-size:40px; margin-bottom:12px; display:block; }

    /* Footer */
    .stmt-footer { background:#F9FAFB; border-top:1px solid #F0F2F7; padding:24px 48px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; }
    .stmt-footer p { font-size:11px; color:#9CA3AF; }
    .stmt-footer .qr-note { font-size:11px; color:#6B7280; text-align:right; }

    /* Print button */
    .no-print { position:fixed; bottom:24px; right:24px; display:flex; gap:10px; z-index:100; }
    .btn-dl { background:linear-gradient(135deg,#6366F1,#8B5CF6); color:#fff; border:none; padding:12px 22px; border-radius:999px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:8px; box-shadow:0 8px 24px rgba(99,102,241,.4); }
    .btn-print { background:#fff; color:#374151; border:1px solid #E5E7EB; padding:12px 22px; border-radius:999px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:8px; box-shadow:0 4px 12px rgba(0,0,0,.08); }

    @media print {
      .no-print { display:none !important; }
      body { background:#fff; }
      .page { box-shadow:none; }
    }

    @media (max-width:640px) {
      .stmt-header { padding:28px 24px 24px; }
      .account-bar { padding:16px 24px; }
      .summary { padding:24px; }
      .summary-grid { grid-template-columns:repeat(2,1fr); }
      .tx-section { padding:24px 24px 40px; }
      .stmt-footer { padding:20px 24px; }
    }
  </style>
</head>
<body>

<div class="page" id="statementPage">

  {{-- Header --}}
  <div class="stmt-header">
    <div class="logo">
      <img src="data:image/png;base64,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"
           alt="Flovide" style="height:40px; width:auto; display:block;">
    </div>
    <div class="meta">
      <div class="meta-left">
        <h1>{{ $balance->currency }} Account Statement</h1>
        <p>Generated on {{ now()->format('jS M Y') }}</p>
      </div>
      <div class="meta-right">
        <p>Statement Period</p>
        <strong>{{ $startDate->format('jS M Y') }} – {{ $endDate->format('jS M Y') }}</strong>
      </div>
    </div>
  </div>

  {{-- Account info bar --}}
  <div class="account-bar">
    <div class="item">
      <label>Account Name</label>
      <span>{{ $user->business_name ?? ($user->firstname . ' ' . $user->lastname) }}</span>
    </div>
    <div class="item">
      <label>Wallet</label>
      <span>{{ $balance->name }}</span>
    </div>
    <div class="item">
      <label>Currency</label>
      <span>{{ $balance->currency }}</span>
    </div>
    <div class="item">
      <label>Account ID</label>
      <span>#{{ $user->id }}</span>
    </div>
  </div>

  {{-- Balance Summary --}}
  <div class="summary">
    <h2>Balance Summary</h2>
    <div class="summary-grid">
      <div class="summary-cell">
        <label>Product</label>
        <div class="val" style="font-size:14px;">{{ $balance->currency }} Wallet</div>
        <div class="note">{{ $balance->name }}</div>
      </div>
      <div class="summary-cell">
        <label>Opening Balance</label>
        <div class="val">{{ $balance->currency_meta['symbol'] ?? '' }}{{ number_format($openingBalance, 2) }}</div>
      </div>
      <div class="summary-cell">
        <label>Total Debits</label>
        <div class="val debit">{{ $balance->currency_meta['symbol'] ?? '' }}{{ number_format($totalDebit, 2) }}</div>
      </div>
      <div class="summary-cell">
        <label>Total Credits</label>
        <div class="val credit">{{ $balance->currency_meta['symbol'] ?? '' }}{{ number_format($totalCredit, 2) }}</div>
      </div>
    </div>

    {{-- Closing balance row --}}
    <div style="margin-top:12px; background:#F5F3FF; border-radius:12px; padding:16px 20px; display:flex; justify-content:space-between; align-items:center;">
      <span style="font-size:13px; font-weight:600; color:#5B21B6;">Closing Balance</span>
      <span style="font-size:20px; font-weight:800; color:#5B21B6;">{{ $balance->currency_meta['symbol'] ?? '' }}{{ number_format($closingBalance, 2) }}</span>
    </div>

    <p style="font-size:11px; color:#9CA3AF; margin-top:12px; line-height:1.6;">
      The balance on your statement might differ from the balance shown in your app.
      The statement balance only reflects completed transactions, while the app shows
      the balance available for use, which accounts for pending transactions.
    </p>
  </div>

  {{-- Transactions --}}
  <div class="tx-section">
    <h2>
      Transactions from {{ $startDate->format('jS M Y') }} – {{ $endDate->format('jS M Y') }}
      @if($transactions->count())
        <span style="font-weight:400; color:#9CA3AF;">({{ $transactions->count() }})</span>
      @endif
    </h2>

    @if($transactions->isEmpty())
      <div class="empty">
        <i class="fas fa-receipt"></i>
        <p>No transactions found in this period.</p>
      </div>
    @else
      @php $runningBalance = $openingBalance; @endphp
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Description</th>
            <th>Type</th>
            <th>Status</th>
            <th class="right">Debit</th>
            <th class="right">Credit</th>
            <th class="right">Balance</th>
          </tr>
        </thead>
        <tbody>
@foreach($transactions as $tx)
  @php
    $type     = strtolower($tx->type ?? '');
    $isCredit = str_contains($type, 'credit');
    $isSwap   = str_contains($type, 'swap');

    if ($isCredit) {
      $runningBalance += $tx->amount;
    } else {
      $runningBalance -= $tx->amount;
    }

    if ($isSwap)        $typeLabel = 'Swap';
    elseif ($isCredit)  $typeLabel = 'Credit';
    else                $typeLabel = 'Debit';

    $sym = $balance->currency_meta['symbol'] ?? '';
  @endphp

  <tr>
    <td>
      {{ \Carbon\Carbon::parse($tx->created_at)->format('M j, Y') }}<br>
      <span class="ref">{{ \Carbon\Carbon::parse($tx->created_at)->format('g:i A') }}</span>
    </td>
    <td>
      {{ $tx->sender ?? $tx->recipient_account_name ?? 'N/A' }}
      <div class="ref">{{ $tx->reference ?? $tx->order_id ?? '' }}</div>
    </td>
    <td>
      @if($isSwap)
        <span class="badge" style="background:#EDE9FE;color:#5B21B6;">Swap</span>
      @elseif($isCredit)
        <span class="badge badge-credit">Credit</span>
      @else
        <span class="badge badge-debit">Debit</span>
      @endif
    </td>
    <td>
      <span class="badge badge-{{ $tx->status }}">{{ ucfirst($tx->status) }}</span>
    </td>
    <td class="right {{ (!$isCredit && !$isSwap) ? 'amount-debit' : '' }}">
      {{ ($isCredit || $isSwap) ? '—' : $sym . number_format($tx->amount, 2) }}
    </td>
    <td class="right {{ $isCredit ? 'amount-credit' : ($isSwap ? '' : '') }}">
      {{ $isCredit ? $sym . number_format($tx->amount, 2) : ($isSwap ? $sym . number_format($tx->amount, 2) : '—') }}
    </td>
    <td class="right" style="color:#1A1D2E;">
      {{ $sym }}{{ number_format($runningBalance, 2) }}
    </td>
  </tr>
@endforeach
        </tbody>
      </table>
    @endif
  </div>

  {{-- Footer --}}
  <div class="stmt-footer">
    <div>
      <p style="font-weight:600; color:#374151; margin-bottom:4px;">Flovide Financial Services</p>
      <p>support@flovide.com · flovide.com</p>
      <p style="margin-top:4px;">Keep this statement for your records.</p>
    </div>
    <div class="qr-note">
      <p style="font-weight:600; color:#374151; margin-bottom:4px;">Your Business Security</p>
      <p>Begins with you</p>
    </div>
  </div>

</div>{{-- /page --}}

{{-- Action buttons --}}
<div class="no-print">
  <button onclick="window.print()" class="btn-print">
    <i class="fas fa-print"></i> Print
  </button>
  <button onclick="downloadPDF()" class="btn-dl">
    <i class="fas fa-download"></i> Download PDF
  </button>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

<script>
const FLOVIDE_LOGO_PNG = 'data:image/png;base64,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';

async function downloadPDF() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF({ unit: 'mm', format: 'a4' });
  const W   = doc.internal.pageSize.getWidth();
  const H   = doc.internal.pageSize.getHeight();
  const sym = '{{ $balance->currency_meta["symbol"] ?? "" }}';

  // ── Dark header ──
  doc.setFillColor(26, 29, 46);
  doc.rect(0, 0, W, 52, 'F');

  // Logo (real Flovide wordmark, native aspect ratio ~2.6:1)
  const logoW = 30, logoH = logoW * (80 / 209);
  doc.addImage(FLOVIDE_LOGO_PNG, 'PNG', 14, 12, logoW, logoH);

  // Title
  doc.setFontSize(11); doc.setFont('helvetica','normal'); doc.setTextColor(180,185,220);
  doc.text('{{ $balance->currency }} Account Statement', 14, 34);

  // Period (right)
  doc.setFontSize(9); doc.setTextColor(180,185,220);
  doc.text('{{ $startDate->format("jS M Y") }} – {{ $endDate->format("jS M Y") }}', W-14, 34, { align:'right' });
  doc.text('Generated: {{ now()->format("jS M Y") }}', W-14, 40, { align:'right' });

  // ── Account bar ──
  doc.setFillColor(45, 53, 97);
  doc.rect(0, 52, W, 18, 'F');
  const accountItems = [
    ['Account', '{{ $user->business_name ?? ($user->firstname . " " . $user->lastname) }}'],
    ['Wallet',  '{{ $balance->name }}'],
    ['Currency','{{ $balance->currency }}'],
    ['Account ID', '#{{ $user->id }}'],
  ];
  accountItems.forEach(([label, val], i) => {
    const x = 14 + i * (W - 28) / 4;
    doc.setFontSize(7); doc.setFont('helvetica','normal'); doc.setTextColor(130,140,180);
    doc.text(label, x, 58);
    doc.setFontSize(9); doc.setFont('helvetica','bold'); doc.setTextColor(255,255,255);
    doc.text(String(val), x, 64);
  });

  // ── Summary table ──
  doc.autoTable({
    startY: 76,
    head: [['Product', 'Opening Balance', 'Total Debits', 'Total Credits', 'Closing Balance']],
    body: [[
      '{{ $balance->currency }} Wallet',
      sym + '{{ number_format($openingBalance, 2) }}',
      sym + '{{ number_format($totalDebit, 2) }}',
      sym + '{{ number_format($totalCredit, 2) }}',
      sym + '{{ number_format($closingBalance, 2) }}',
    ]],
    headStyles: { fillColor:[99,102,241], textColor:255, fontStyle:'bold', fontSize:9 },
    bodyStyles: { fontSize:9 },
    margin: { left:14, right:14 },
    columnStyles: { 2:{ textColor:[220,38,38] }, 3:{ textColor:[5,150,105] }, 4:{ fontStyle:'bold' } },
  });

  // ── Transactions table ──
  const txRows = [
    @foreach($transactions as $tx)
    @php
        $type2     = strtolower($tx->type ?? '');
        $isCredit2 = str_contains($type2, 'credit');
        $isSwap2   = str_contains($type2, 'swap');

        if ($isSwap2)       $pdfLabel = 'Swap';
        elseif ($isCredit2) $pdfLabel = 'Credit';
        else                $pdfLabel = 'Debit';

        $sym2 = $balance->currency_meta['symbol'] ?? '';
    @endphp
    [
        '{{ \Carbon\Carbon::parse($tx->created_at)->format("M j, Y g:i A") }}',
        '{{ addslashes($tx->sender ?? $tx->recipient_account_name ?? "N/A") }}',
        '{{ $pdfLabel }}',
        '{{ ucfirst($tx->status) }}',
        '{{ (!$isCredit2 && !$isSwap2) ? $sym2 . number_format($tx->amount, 2) : "—" }}',
        '{{ ($isCredit2 || $isSwap2) ? $sym2 . number_format($tx->amount, 2) : "—" }}',
    ],
    @endforeach
  ];

  doc.autoTable({
    startY: doc.lastAutoTable.finalY + 10,
    head: [['Date', 'Description', 'Type', 'Status', 'Debit', 'Credit']],
    body: txRows.length ? txRows : [['', 'No transactions in this period', '', '', '', '']],
    headStyles: { fillColor:[26,29,46], textColor:255, fontStyle:'bold', fontSize:8 },
    bodyStyles: { fontSize:8 },
    margin: { left:14, right:14 },
    didParseCell(data) {
      if (data.section === 'body') {
        if (data.column.index === 2 && data.cell.raw === 'Credit') data.cell.styles.textColor = [5,150,105];
        if (data.column.index === 2 && data.cell.raw === 'Debit')  data.cell.styles.textColor = [220,38,38];
        if (data.column.index === 4) data.cell.styles.textColor = [220,38,38];
        if (data.column.index === 5) data.cell.styles.textColor = [5,150,105];
      }
    },
    alternateRowStyles: { fillColor:[249,250,251] },
  });

  // ── Footer ──
  const finalY = doc.lastAutoTable.finalY + 10;
  doc.setFillColor(249,250,251);
  doc.rect(0, finalY, W, 20, 'F');
  doc.setFontSize(8); doc.setFont('helvetica','normal'); doc.setTextColor(107,114,128);
  doc.text('Flovide Financial Services · support@flovide.com', 14, finalY + 8);
  doc.text('Keep this statement for your records.', 14, finalY + 14);

  doc.save(`Flovide-Statement-{{ $balance->currency }}-{{ $startDate->format("Y-m-d") }}-to-{{ $endDate->format("Y-m-d") }}.pdf`);
}
</script>

</body>
</html>