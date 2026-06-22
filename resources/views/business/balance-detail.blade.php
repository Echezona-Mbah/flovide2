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
              $credits   = $transactions->where('transaction_type','!=','payment')->sum('amount');
              $debits    = $transactions->where('transaction_type','payment')->sum('amount');
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

        @if($balance->currency === 'NGN' && auth()->user()->virtual_account_number)
          <div class="flex items-start gap-4 p-5 rounded-2xl bg-gradient-to-r from-green-50 to-emerald-50 border border-green-100">
            <span class="text-3xl">🇳🇬</span>
            <div class="flex-1">
              <p class="font-bold text-sm text-gray-800 mb-3">Fidelity Bank — NGN</p>
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-y-3 gap-x-6 text-sm">
                <div><p class="text-gray-400 text-xs mb-0.5">Account Number</p><p class="font-semibold">{{ auth()->user()->virtual_account_number }}</p></div>
                <div><p class="text-gray-400 text-xs mb-0.5">Account Name</p><p class="font-semibold">{{ auth()->user()->virtual_account_name ?? 'N/A' }}</p></div>
                <div><p class="text-gray-400 text-xs mb-0.5">Bank</p><p class="font-semibold">{{ auth()->user()->virtual_account_bank ?? 'N/A' }}</p></div>
              </div>
            </div>
          </div>

        @elseif($balance->currency === 'CAD' && auth()->user()->blaaiz_id)
          <div class="flex items-start gap-4 p-5 rounded-2xl bg-gradient-to-r from-red-50 to-rose-50 border border-red-100">
            <span class="text-3xl">🇨🇦</span>
            <div class="flex-1">
              <p class="font-bold text-sm text-gray-800 mb-3">Blaaiz — CAD (Interac)</p>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-6 text-sm">
                <div><p class="text-gray-400 text-xs mb-0.5">Customer ID</p><p class="font-semibold">{{ auth()->user()->blaaiz_id }}</p></div>
                <div><p class="text-gray-400 text-xs mb-0.5">Method</p><p class="font-semibold">Interac e-Transfer</p></div>
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
  </main>

  <script>
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

      // ── LOGO: circular orbit + "Flovide" text ──
      const cx = W / 2, cy = 20;

      // Outer orbit ring
      doc.setDrawColor(99, 102, 241);
      doc.setLineWidth(0.6);
      doc.circle(cx, cy, 9);

      // Tilted inner orbit ellipse (simulated with arc points)
      doc.setDrawColor(139, 92, 246);
      doc.setLineWidth(0.4);
      doc.ellipse(cx, cy, 9, 3.5, 'S');

      // Centre dot
      doc.setFillColor(99, 102, 241);
      doc.circle(cx, cy, 1.8, 'F');

      // Orbit dot (top-right of ring)
      doc.setFillColor(139, 92, 246);
      doc.circle(cx + 6.5, cy - 6, 1.2, 'F');

      // "Flovide" wordmark
      doc.setFontSize(13);
      doc.setFont('helvetica', 'bold');
      doc.setTextColor(255, 255, 255);
      doc.text('Flovide', W / 2, 35, { align: 'center' });

      // Tagline
      doc.setFontSize(7);
      doc.setFont('helvetica', 'normal');
      doc.setTextColor(130, 135, 165);
      doc.text('Transaction Receipt', W / 2, 40, { align: 'center' });

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
        // Row bg pill for alternating rows
        doc.setFontSize(7.5);
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(110, 115, 150);
        doc.text(label, 14, y);

        doc.setFont('helvetica', 'bold');
        doc.setTextColor(220, 225, 245);
        const v = String(value ?? 'N/A');
        const wrapped = doc.splitTextToSize(v, 52);
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
  </script>
</body>
</html>