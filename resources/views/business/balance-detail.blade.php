@include('business.head')

<body class="bg-[#F0F2F7] text-[#1A1D2E] min-h-screen flex flex-col md:flex-row">
  @include('business.header')
  @include('business.sidebar')

  <div id="overlay" class="fixed inset-0 bg-black/40 z-20 hidden md:hidden"></div>

  {{-- Google Fonts + jsPDF --}}
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

  <style>
    body { font-family: 'Inter', sans-serif; }
    .hero-card {
      background: linear-gradient(135deg, #1A1D2E 0%, #2D3561 60%, #1A1D2E 100%);
      position: relative;
      overflow: hidden;
    }
    .hero-card::before {
      content: '';
      position: absolute;
      top: -60px; right: -60px;
      width: 260px; height: 260px;
      border-radius: 50%;
      background: rgba(99,102,241,0.18);
    }
    .hero-card::after {
      content: '';
      position: absolute;
      bottom: -40px; left: -40px;
      width: 180px; height: 180px;
      border-radius: 50%;
      background: rgba(139,92,246,0.12);
    }
    .badge-credit  { background:#D1FAE5; color:#065F46; }
    .badge-debit   { background:#FEE2E2; color:#991B1B; }
    .badge-swap    { background:#EDE9FE; color:#5B21B6; }
    .badge-withdraw{ background:#FEF3C7; color:#92400E; }
    .badge-pending { background:#FEF9C3; color:#854D0E; }
    .badge-success { background:#D1FAE5; color:#065F46; }
    .badge-failed  { background:#FEE2E2; color:#991B1B; }
    .tx-icon-credit  { background:#D1FAE5; color:#059669; }
    .tx-icon-debit   { background:#FEE2E2; color:#DC2626; }
    .tx-icon-swap    { background:#EDE9FE; color:#7C3AED; }
    .tx-icon-withdraw{ background:#FEF3C7; color:#D97706; }
    .stat-pill {
      background: rgba(255,255,255,0.08);
      border: 1px solid rgba(255,255,255,0.12);
      border-radius: 999px;
      backdrop-filter: blur(8px);
    }
    .action-btn-primary {
      background: linear-gradient(135deg,#6366F1,#8B5CF6);
      color: #fff;
      border: none;
    }
    .action-btn-primary:hover { opacity:.88; }
    .action-btn-ghost {
      background: rgba(255,255,255,0.1);
      border: 1px solid rgba(255,255,255,0.18);
      color: #fff;
    }
    .action-btn-ghost:hover { background: rgba(255,255,255,0.18); }
    .tx-row { transition: background .15s; }
    .tx-row:hover { background: #F5F7FF; }
    .section-title {
      font-family: 'Space Grotesk', sans-serif;
      font-weight: 700;
      letter-spacing: -0.02em;
    }
    @media print { .no-print { display:none !important; } }
  </style>

  <main class="flex-1 p-3 md:p-8 overflow-auto">

    {{-- Top nav --}}
    <header class="hidden md:flex items-center justify-between mb-7 gap-4 no-print">
      <div class="flex items-center gap-3">
        <a href="{{ route('dashboard') }}" class="w-9 h-9 flex items-center justify-center rounded-full bg-white border border-gray-200 hover:bg-gray-50 transition shadow-sm">
          <i class="fas fa-arrow-left text-sm text-gray-600"></i>
        </a>
        <span class="text-sm text-gray-400 font-medium">Wallets /</span>
        <span class="text-sm font-semibold text-gray-700">{{ $balance->name }}</span>
      </div>
      @include('business.header_notifical')
    </header>

    {{-- Alerts --}}
    @if(session('success'))
      <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-2xl text-sm no-print">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl text-sm no-print">{{ session('error') }}</div>
    @endif

    <div class="max-w-4xl mx-auto space-y-5">

      {{-- ── HERO CARD ── --}}
      <div class="hero-card rounded-3xl p-7 md:p-10 text-white relative z-0">
        <div class="relative z-10">
          <div class="flex items-start justify-between flex-wrap gap-4 mb-8">
            <div class="flex items-center gap-3">
              <img src="https://flagcdn.com/w40/{{ strtolower($balance->currency_meta['country']) }}.png"
                   alt="flag" class="w-10 h-7 rounded-md object-cover shadow"/>
              <div>
                <p class="text-xs font-semibold uppercase tracking-[.25em] text-indigo-200">{{ $balance->currency }} Wallet</p>
                <p class="text-sm text-white/50 mt-0.5">{{ $balance->name }}</p>
              </div>
            </div>
            {{-- Action buttons --}}
            <div class="flex gap-2 no-print flex-wrap">
              <a href="{{ route('add_money') }}"
                 class="action-btn-ghost inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold transition">
                <i class="fas fa-plus text-xs"></i> Add
              </a>
              <a href="{{ route('send') }}"
                 class="action-btn-primary inline-flex items-center gap-2 px-5 py-2 rounded-full text-sm font-semibold transition shadow-lg shadow-indigo-900/40">
                <i class="fas fa-paper-plane text-xs"></i> Send
              </a>
              {{-- Statement button --}}
              <button onclick="openStatementModal()"
                class="action-btn-ghost inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold transition no-print">
                <i class="fas fa-file-alt text-xs"></i> Statement
              </button>
            </div>
          </div>

          {{-- Balance --}}
          <div class="mb-6">
            <p class="text-white/50 text-xs font-medium uppercase tracking-widest mb-1">Available Balance</p>
            <h2 class="text-4xl md:text-5xl font-black tracking-tight" style="font-family:'Space Grotesk',sans-serif;">
              {{ $balance->currency_meta['symbol'] }}{{ number_format($balance->amount, 2) }}
            </h2>
          </div>

          {{-- Stats pills --}}
          <div class="flex flex-wrap gap-3 text-xs font-semibold">
            @php
              $credits   = $transactions->where('type','Credit')->sum('amount');
              $debits    = $transactions->where('type','withdrawal')->sum('amount');
              $swaps     = $transactions->whereIn('type',['swap','Swap'])->count();
            @endphp
            <span class="stat-pill px-4 py-2 flex items-center gap-2">
              <i class="fas fa-arrow-down-left text-emerald-400"></i>
              {{ $balance->currency_meta['symbol'] }}{{ number_format($credits,2) }} in
            </span>
            <span class="stat-pill px-4 py-2 flex items-center gap-2">
              <i class="fas fa-arrow-up-right text-red-400"></i>
              {{ $balance->currency_meta['symbol'] }}{{ number_format($debits,2) }} out
            </span>
            <span class="stat-pill px-4 py-2 flex items-center gap-2">
              <i class="fas fa-arrows-rotate text-violet-400"></i>
              {{ $swaps }} swap{{ $swaps !== 1 ? 's' : '' }}
            </span>
            <span class="stat-pill px-4 py-2 flex items-center gap-2">
              <i class="fas fa-receipt text-white/60"></i>
              {{ $transactions->total() }} txns
            </span>
          </div>
        </div>
      </div>

      {{-- ── ACCOUNT DETAILS ── --}}
      <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8">
        <h3 class="section-title text-base mb-5">Account Details</h3>

        @if($balance->currency === 'NGN' && $balance->virtual_account_number)
          <div class="flex items-start gap-4 p-5 rounded-2xl bg-gradient-to-r from-green-50 to-emerald-50 border border-green-100">
            <span class="text-3xl">🇳🇬</span>
            <div class="flex-1">
              <p class="font-bold text-sm text-gray-800 mb-3">Fidelity Bank — NGN</p>
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-y-3 gap-x-6 text-sm">
                <div><p class="text-gray-400 text-xs mb-0.5">Account Number</p><p class="font-semibold">{{ $balance->virtual_account_number }}</p></div>
                <div><p class="text-gray-400 text-xs mb-0.5">Account Name</p><p class="font-semibold">{{ $balance->virtual_account_name ?? 'N/A' }}</p></div>
                <div><p class="text-gray-400 text-xs mb-0.5">Bank</p><p class="font-semibold">{{ $balance->virtual_account_bank ?? 'N/A' }}</p></div>
              </div>
            </div>
          </div>

       @elseif($balance->currency === 'CAD' )
      <div class="flex items-start gap-4 p-5 rounded-2xl bg-gradient-to-r from-red-50 to-rose-50 border border-red-100">
        <span class="text-3xl">🇨🇦</span>
        <div class="flex-1">
          <p class="font-bold text-sm text-gray-800 mb-3">Flovide — CAD (Interac)</p>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-6 text-sm">
            {{-- <div><p class="text-gray-400 text-xs mb-0.5">Customer ID</p><p class="font-semibold">{{ auth()->user()->blaaiz_id }}</p></div> --}}
            <div><p class="text-gray-400 text-xs mb-0.5">Method</p><p class="font-semibold">Interac e-Transfer</p></div>
          </div>

          {{-- Auto Deposit button --}}
         {{-- Auto Deposit --}}
          <div class="mt-4 pt-4 border-t border-red-100 flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-2">
              <i class="fas fa-bolt text-red-500 text-sm"></i>
              <div>
                <p class="text-xs font-semibold text-gray-700">Interac Auto Deposit</p>
                <p class="text-[11px] text-gray-400">
                  {{ $autoDeposits->count() }} email{{ $autoDeposits->count() !== 1 ? 's' : '' }} registered
                </p>
              </div>
            </div>
            <a href="{{ route('balance.interac_autodeposit', $balance->id) }}"
              class="inline-flex items-center gap-2 bg-white border border-red-200 text-red-600 text-xs font-semibold px-3.5 py-2 rounded-full hover:bg-red-50 transition no-print">
              <i class="fas fa-list text-[11px]"></i> Manage Auto Deposit Emails
            </a>
          </div>
        </div>
      </div>

        @else
          <div class="flex flex-col items-center justify-center py-10 text-center border border-dashed border-gray-200 rounded-2xl bg-gray-50">
            <i class="fas fa-building-columns text-2xl text-gray-300 mb-2"></i>
            <p class="text-sm text-gray-400">No linked bank account for this wallet yet.</p>
          </div>
        @endif
      </div>

      {{-- ── TRANSACTIONS ── --}}
      <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8">
        <div class="flex items-center justify-between mb-6">
          <h3 class="section-title text-base">Transaction History</h3>
          <span class="text-xs font-medium bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full">{{ $transactions->total() }} total</span>
        </div>

        <div class="space-y-2">
          @forelse($transactions as $tx)
            @php
              $txType = strtolower($tx->type ?? '');
              $isPayment = $tx->transaction_type === 'payment';
              $isSwap    = str_contains($txType, 'swap');
              $isWithdraw= str_contains($txType, 'withdraw') || str_contains($txType, 'withdrawal');
              $isCredit  = !$isPayment && !$isSwap && !$isWithdraw;

              if ($isSwap)         [$iconClass,$icon,$label] = ['tx-icon-swap','fa-arrows-rotate','Swap'];
              elseif ($isWithdraw) [$iconClass,$icon,$label] = ['tx-icon-withdraw','fa-money-bill-transfer','Withdraw'];
              elseif ($isPayment)  [$iconClass,$icon,$label] = ['tx-icon-debit','fa-arrow-up-right','Debit'];
              else                 [$iconClass,$icon,$label] = ['tx-icon-credit','fa-arrow-down-left','Credit'];

              $badgeType = $isSwap ? 'swap' : ($isWithdraw ? 'withdraw' : ($isPayment ? 'debit' : 'credit'));
              $statusBadge = $tx->status === 'success' ? 'badge-success' : ($tx->status === 'pending' ? 'badge-pending' : 'badge-failed');
            @endphp

            <div class="tx-row flex items-center justify-between rounded-2xl px-4 py-3.5 cursor-default">
              {{-- Icon + info --}}
              <div class="flex items-center gap-3.5 min-w-0">
                <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center {{ $iconClass }}">
                  <i class="fas {{ $icon }} text-sm"></i>
                </div>
                <div class="min-w-0">
                  <p class="text-sm font-semibold truncate max-w-[180px]">
                    {{ $tx->sender ?? $tx->recipient_account_name ?? 'No name' }}
                  </p>
                  <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                    <span class="text-[11px] badge-{{ $badgeType }} px-2 py-0.5 rounded-full font-semibold">{{ $label }}</span>
                    <span class="text-[11px] {{ $statusBadge }} px-2 py-0.5 rounded-full font-semibold">{{ ucfirst($tx->status) }}</span>
                    <span class="text-[11px] text-gray-400">{{ \Carbon\Carbon::parse($tx->created_at)->format('M j, Y · g:i A') }}</span>
                  </div>
                  <p class="text-[11px] text-gray-300 mt-0.5 truncate">Ref: {{ $tx->reference ?? $tx->order_id ?? 'N/A' }}</p>
                </div>
              </div>

              {{-- Amount + download --}}
              <div class="flex items-center gap-3 flex-shrink-0 ml-3">
                <p class="font-bold text-sm md:text-base text-right {{ $isPayment || $isWithdraw ? 'text-red-500' : ($isSwap ? 'text-violet-600' : 'text-emerald-600') }}">
                  {{ $isPayment || $isWithdraw ? '−' : '+' }}{{ number_format($tx->amount, 2) }} {{ $tx->currency }}
                </p>
                {{-- Download receipt button --}}
                <button
                  onclick="downloadReceipt(this)"
                  data-ref="{{ $tx->reference ?? $tx->order_id ?? 'N/A' }}"
                  data-type="{{ $label }}"
                  data-amount="{{ ($isPayment || $isWithdraw ? '−' : '+') . number_format($tx->amount,2) . ' ' . $tx->currency }}"
                  data-date="{{ \Carbon\Carbon::parse($tx->created_at)->format('M j, Y g:i A') }}"
                  data-sender="{{ $tx->sender ?? 'N/A' }}"
                  data-recipient="{{ $tx->recipient_account_name ?? 'N/A' }}"
                  data-status="{{ ucfirst($tx->status) }}"
                  data-method="{{ $tx->method ?? 'N/A' }}"
                  class="no-print flex-shrink-0 w-8 h-8 rounded-full bg-gray-100 hover:bg-indigo-100 hover:text-indigo-600 flex items-center justify-center transition text-gray-400"
                  title="Download receipt">
                  <i class="fas fa-download text-xs pointer-events-none"></i>
                </button>
              </div>
            </div>

            @if(!$loop->last)
              <div class="border-b border-gray-50 mx-4"></div>
            @endif

          @empty
            <div class="flex flex-col items-center py-16 text-center">
              <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                <i class="fas fa-receipt text-xl text-gray-300"></i>
              </div>
              <p class="text-gray-400 text-sm">No transactions yet for this wallet.</p>
            </div>
          @endforelse
        </div>

        <div class="mt-6">{{ $transactions->links() }}</div>
      </div>

    </div>{{-- /max-w --}}



    {{-- ── Statement Modal ──────────────────────────────────────────────────── --}}
    <div id="statementModal" class="fixed inset-0 z-[70] bg-black/50 backdrop-blur-sm hidden items-center justify-center p-4 no-print">
      <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md relative overflow-hidden">

        {{-- Header --}}
        <div class="px-6 pt-6 pb-5 border-b border-gray-100">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                <i class="fas fa-file-invoice text-indigo-600 text-sm"></i>
              </div>
              <div>
                <h2 class="font-bold text-gray-800">Account Statement</h2>
                <p class="text-xs text-gray-400">{{ $balance->currency }} · {{ $balance->name }}</p>
              </div>
            </div>
            <button onclick="closeStatementModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400">
              <i class="fas fa-times text-sm"></i>
            </button>
          </div>
        </div>

        {{-- Body --}}
        <form method="GET" action="{{ route('balance.statement', $balance->id) }}" target="_blank">
          <div class="p-6 space-y-5">

            <div>
              <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Start Date</label>
              <input type="date" name="start_date" id="stmtStart"
                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent"
                value="{{ now()->startOfMonth()->toDateString() }}" required>
            </div>

            <div>
              <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">End Date</label>
              <input type="date" name="end_date" id="stmtEnd"
                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent"
                value="{{ now()->toDateString() }}" required>
            </div>

            {{-- Quick range pills --}}
            <div>
              <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Quick Range</p>
              <div class="flex flex-wrap gap-2">
                <button type="button" onclick="setRange(7)"
                  class="px-3 py-1.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 transition">Last 7 days</button>
                <button type="button" onclick="setRange(30)"
                  class="px-3 py-1.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 transition">Last 30 days</button>
                <button type="button" onclick="setRange(90)"
                  class="px-3 py-1.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 transition">Last 90 days</button>
                <button type="button" onclick="setThisMonth()"
                  class="px-3 py-1.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 transition">This Month</button>
              </div>
            </div>

          </div>

          <div class="px-6 pb-6">
            <button type="submit"
              class="w-full bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold py-3.5 rounded-2xl hover:opacity-90 transition flex items-center justify-center gap-2 shadow-lg shadow-indigo-200">
              <i class="fas fa-download text-sm"></i> Generate Statement
            </button>
          </div>
        </form>

      </div>
    </div>

  </main>

  <script>
  // Flovide logo, embedded as base64 so the receipt never depends on a
  // network fetch / CORS-safe canvas read.
  const FLOVIDE_LOGO_PNG = 'data:image/png;base64,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';

  async function downloadReceipt(btn) {
    const { jsPDF } = window.jspdf;
    const d = btn.dataset;
    const doc = new jsPDF({ unit: 'mm', format: 'a6' });
    const W = doc.internal.pageSize.getWidth(); // 105mm
    const H = 148;

    // ── Background ──
    doc.setFillColor(26, 29, 46);
    doc.rect(0, 0, W, H, 'F');

    // ── Subtle top accent strip ──
    doc.setFillColor(99, 102, 241);
    doc.rect(0, 0, W, 1.2, 'F');

    // ── LOGO: real Flovide logo, no background panel ──
    // Source PNG is 209x80 (aspect ratio ~2.6:1)
    const logoW = 32, logoH = logoW * (80 / 209);
    const logoX = W / 2 - logoW / 2;
    const logoY = 10;

    doc.addImage(FLOVIDE_LOGO_PNG, 'PNG', logoX, logoY, logoW, logoH);

    // Tagline
    doc.setFontSize(7);
    doc.setFont('helvetica', 'normal');
    doc.setTextColor(130, 135, 165);
    doc.text('Transaction Receipt', W / 2, logoY + logoH + 6, { align: 'center' });

    // ── Success badge ──
    const isSuccess = d.status.toLowerCase() === 'success';
    const isPending = d.status.toLowerCase() === 'pending';
    const badgeColor = isSuccess ? [5,150,105] : isPending ? [217,119,6] : [220,38,38];
    doc.setFillColor(...badgeColor);
    doc.roundedRect(W/2 - 14, 43, 28, 7, 3.5, 3.5, 'F');
    doc.setFontSize(7);
    doc.setFont('helvetica', 'bold');
    doc.setTextColor(255,255,255);
    doc.text(d.status.toUpperCase(), W/2, 47.8, { align: 'center' });

    // ── Amount ──
    doc.setFontSize(22);
    doc.setFont('helvetica', 'bold');
    const amtColor = d.amount.startsWith('+') ? [5,150,105] : [220,38,38];
    doc.setTextColor(...amtColor);
    doc.text(d.amount, W / 2, 60, { align: 'center' });

    // Date
    doc.setFontSize(7.5);
    doc.setFont('helvetica', 'normal');
    doc.setTextColor(130, 135, 165);
    doc.text(d.date, W / 2, 66, { align: 'center' });

    // ── Divider ──
    doc.setDrawColor(45, 53, 97);
    doc.setLineWidth(0.4);
    doc.line(12, 70, W - 12, 70);

    // ── Detail rows ──
    const rows = [
      ['Transaction Type', d.type],
      ['Sender',           d.sender],
      ['Recipient',        d.recipient],
      ['Method',           d.method],
      ['Reference',        d.ref],
    ];

    let y = 78;
    rows.forEach(([label, value]) => {
      doc.setFontSize(7.5);
      doc.setFont('helvetica', 'normal');
      doc.setTextColor(110, 115, 150);
      doc.text(label, 14, y);

      doc.setFont('helvetica', 'bold');
      doc.setTextColor(220, 225, 245);
      const v = String(value ?? 'N/A');
      const wrapped = doc.splitTextToSize(v, 58);
      doc.text(wrapped, W - 14, y, { align: 'right' });
      y += wrapped.length > 1 ? wrapped.length * 5.5 : 10;
    });

    // ── Bottom divider ──
    doc.setDrawColor(45, 53, 97);
    doc.line(12, H - 14, W - 12, H - 14);

    // ── Footer ──
    doc.setFontSize(6.5);
    doc.setFont('helvetica', 'normal');
    doc.setTextColor(80, 85, 115);
    doc.text('Flovide Financial Services  ·  support@flovide.com', W / 2, H - 9, { align: 'center' });
    doc.text('Keep this receipt for your records', W / 2, H - 5, { align: 'center' });

    doc.save(`Flovide-receipt-${d.ref}.pdf`);
  }

    function openStatementModal() {
  const m = document.getElementById('statementModal');
  m.classList.remove('hidden');
  m.classList.add('flex');
}

function closeStatementModal() {
  const m = document.getElementById('statementModal');
  m.classList.add('hidden');
  m.classList.remove('flex');
}

// Quick range helpers
function setRange(days) {
  const end   = new Date();
  const start = new Date();
  start.setDate(end.getDate() - days);
  document.getElementById('stmtStart').value = start.toISOString().split('T')[0];
  document.getElementById('stmtEnd').value   = end.toISOString().split('T')[0];
}

function setThisMonth() {
  const now   = new Date();
  const start = new Date(now.getFullYear(), now.getMonth(), 1);
  document.getElementById('stmtStart').value = start.toISOString().split('T')[0];
  document.getElementById('stmtEnd').value   = now.toISOString().split('T')[0];
}

// Close on backdrop click
document.getElementById('statementModal')?.addEventListener('click', function(e) {
  if (e.target === this) closeStatementModal();
});
  </script>



</body>
</html>